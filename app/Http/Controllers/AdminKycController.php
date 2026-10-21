<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\KycDetail;

class AdminKycController extends Controller
{
    /**
     * Helper function to apply search filters across all states
     * (Username, Name, and Mobile Number)
     */
    private function applyFilters(Request $request, $query)
    {
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            $query->where(function ($q) use ($search) {
                // ૧. KycDetail ટેબલની અંદરના પોતાના fields માં સર્ચ (name, mobile)
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('mobile', 'like', '%' . $search . '%')
                  // ૨. User રિલેશનશિપ ટેબલની અંદર સર્ચ (username, name)
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('username', 'like', '%' . $search . '%')
                                ->orWhere('name', 'like', '%' . $search . '%');
                  });
            });
        }

        return $query;
    }

    /**
     * Pending KYC Requests
     * જો કોઈપણ એક સેક્શન 'submitted' હોય
     */
    public function index(Request $request)
    {
        // Only the sections a member can actually submit are considered - see
        // KycDetail::ACTIVE_SECTIONS. Including the disabled 'personal' section
        // kept these lists permanently empty.
        $query = KycDetail::with('user')->whereAnySection('submitted');

        // ફિલ્ટર એપ્લાય કરો
        $query = $this->applyFilters($request, $query);

        $kycRequests = $query->latest()->get();

        return view('admin.kyc.index', compact('kycRequests'));
    }
    
    /**
     * Approved KYC List
     *
     * Fully approved rows, plus members who still have at least one approved
     * section after admin deleted a document (that section is pending again).
     * Submitted / rejected sections stay on the Pending / Rejected tabs.
     */
    public function approvedindex(Request $request)
    {
        $query = KycDetail::with('user')
            ->whereAnySection('approved')
            ->where(function ($q) {
                foreach (KycDetail::ACTIVE_SECTIONS as $section) {
                    $q->where(function ($s) use ($section) {
                        $s->where("kyc_status_{$section}", 'approved')
                            ->orWhere("kyc_status_{$section}", 'pending')
                            ->orWhereNull("kyc_status_{$section}");
                    });
                }
            });

        // ફિલ્ટર એપ્લાય કરો
        $query = $this->applyFilters($request, $query);

        $kycRequests = $query->latest()->get();

        return view('admin.kyc.approvedindex', compact('kycRequests'));
    }

    /**
     * Rejected KYC List
     * કોઈપણ એક સેક્શન 'rejected' હોય
     */
    public function rejectindex(Request $request)
    {
        $query = KycDetail::with('user')->whereAnySection('rejected');

        // ફિલ્ટર એપ્લાય કરો
        $query = $this->applyFilters($request, $query);

        $kycRequests = $query->latest()->get();

        return view('admin.kyc.rejectindex', compact('kycRequests'));
    }

    public function approve(Request $request, KycDetail $kycDetail)
    {
        $section = $request->input('section');

        if (!in_array($section, ['personal', 'identity', 'pan', 'bank'])) {
            return redirect()->back()->with('error', 'Invalid KYC section.');
        }

        $statusField = 'kyc_status_' . $section;
        $remarkField = $section . '_remark';

        $kycDetail->update([
            $statusField => 'approved',
            $remarkField => null,
        ]);

        return redirect()->back()->with('success', ucfirst($section) . ' section approved successfully.');
    }

    public function reject(Request $request, KycDetail $kycDetail)
    {
        $request->validate(['remark' => 'required|string']);
        $section = $request->input('section');

        if (!in_array($section, ['personal', 'identity', 'pan', 'bank'])) {
            return redirect()->back()->with('error', 'Invalid KYC section.');
        }

        $statusField = 'kyc_status_' . $section;
        $remarkField = $section . '_remark';

        // Fix for identity remark mismatch in your db layout (kyc_remark vs identity_remark)
        if ($section == 'identity') {
            $remarkField = 'kyc_remark'; 
        }

        $kycDetail->update([
            $statusField => 'rejected',
            $remarkField => $request->input('remark'),
        ]);

        return redirect()->back()->with('success', ucfirst($section) . ' section rejected successfully.');
    }

    /**
     * Delete one KYC document section (files + DB fields) so the member can
     * upload that section again. Mirrors approve/reject section handling.
     */
    public function deleteDocument(Request $request, KycDetail $kycDetail)
    {
        $section = $request->input('section');

        if (!in_array($section, ['personal', 'identity', 'pan', 'bank'], true)) {
            return redirect()->back()->with('error', 'Invalid KYC section.');
        }

        $disk = Storage::disk('public');
        $updates = [];

        $deleteFile = function (?string $path) use ($disk) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        };

        // Status goes back to pending so the member can re-upload, but a remark
        // stays so Approved KYC still lists them and shows which doc was removed.
        $deletedRemark = 'Document deleted by admin. Member must re-upload.';

        if ($section === 'personal') {
            $deleteFile($kycDetail->profile_pic);
            $updates = [
                'profile_pic' => null,
                'name' => null,
                'email' => null,
                'mobile' => null,
                'state' => null,
                'address' => null,
                'kyc_status_personal' => 'pending',
                'personal_remark' => $deletedRemark,
            ];
        } elseif ($section === 'identity') {
            $deleteFile($kycDetail->front_image);
            $deleteFile($kycDetail->back_image);
            $updates = [
                'attached_doc' => null,
                'tax_id' => null,
                'front_image' => null,
                'back_image' => null,
                'kyc_status_identity' => 'pending',
                'kyc_remark' => $deletedRemark,
                'identity_remark' => $deletedRemark,
            ];
        } elseif ($section === 'pan') {
            $deleteFile($kycDetail->front_image_pan);
            $updates = [
                'pan_no' => null,
                'front_image_pan' => null,
                'kyc_status_pan' => 'pending',
                'pan_remark' => $deletedRemark,
            ];
        } else { // bank
            $deleteFile($kycDetail->front_image_bank);
            $updates = [
                'account_holder_name' => null,
                'bank_name' => null,
                'account_no' => null,
                'ifsc_code' => null,
                'bank_branch' => null,
                'front_image_bank' => null,
                'kyc_status_bank' => 'pending',
                'bank_remark' => $deletedRemark,
            ];
        }

        $kycDetail->update($updates);

        return redirect()->back()->with(
            'success',
            ucfirst($section) . ' documents deleted. The member can upload new documents for this section.'
        );
    }
}