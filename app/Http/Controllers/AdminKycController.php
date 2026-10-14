<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * બધા જ સેક્શન 'approved' હોવા જોઈએ
     */
    public function approvedindex(Request $request)
    {
        $query = KycDetail::with('user')->whereAllSections('approved');

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
}