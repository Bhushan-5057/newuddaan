<?php
// app/Http/Controllers/UserKycController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\KycDetail;
use Illuminate\Support\Facades\Storage;

class UserKycController extends Controller
{
    /**
     * Display the KYC details form.
     */
    public function index()
    {
        $user = Auth::user();
        $kycDetails = KycDetail::firstOrCreate(['user_id' => $user->id]);
        
        return view('user.kyc.index', compact('kycDetails', 'user'));
    }

    /**
     * Handle the submission of personal details.
     */
    public function submitPersonal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('active_tab', 'tab-1');
        }

        $user = Auth::user();
        $kycDetails = KycDetail::where('user_id', $user->id)->first();

        // Check if the personal section is already submitted or approved
        if ($kycDetails && in_array($kycDetails->kyc_status_personal, ['submitted', 'approved'])) {
            return redirect()->back()->with('error', 'Personal details are already submitted.');
        }

        $profilePicPath = $kycDetails->profile_pic;
        if ($request->hasFile('profile_pic')) {
            if ($profilePicPath && Storage::disk('public')->exists($profilePicPath)) {
                Storage::disk('public')->delete($profilePicPath);
            }
            $profilePicPath = $request->file('profile_pic')->store('kyc/profile_pics', 'public');
        }

        $kycDetails->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'kyc_status_personal' => 'submitted',
            'profile_pic' => $profilePicPath,
        ]);
        
        return redirect()->back()->with('success', 'Personal details submitted successfully.')->with('active_tab', 'tab-1');
    }

    /**
     * Handle the submission of identity verification details.
     */
    public function submitIdentity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attached_doc' => 'required|string',
            'tax_id' => 'required|string|max:255',
            'front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('active_tab', 'tab-2');
        }

        $user = Auth::user();
        $kycDetails = KycDetail::where('user_id', $user->id)->first();
        
        if ($kycDetails && in_array($kycDetails->kyc_status_identity, ['submitted', 'approved'])) {
            return redirect()->back()->with('error', 'Identity details are already submitted.');
        }

        $frontImagePath = $kycDetails->front_image;
        $backImagePath = $kycDetails->back_image;

        if ($request->hasFile('front_image')) {
            if ($frontImagePath && Storage::disk('public')->exists($frontImagePath)) {
                Storage::disk('public')->delete($frontImagePath);
            }
            $frontImagePath = $request->file('front_image')->store('kyc/identity', 'public');
        }

        if ($request->hasFile('back_image')) {
            if ($backImagePath && Storage::disk('public')->exists($backImagePath)) {
                Storage::disk('public')->delete($backImagePath);
            }
            $backImagePath = $request->file('back_image')->store('kyc/identity', 'public');
        }

        $kycDetails->update([
            'attached_doc' => $request->attached_doc,
            'tax_id' => $request->tax_id,
            'front_image' => $frontImagePath,
            'back_image' => $backImagePath,
            'kyc_status_identity' => 'submitted',
            'kyc_remark' => null,
            'identity_remark' => null,
        ]);

        return redirect()->back()->with('success', 'Identity details submitted successfully.')->with('active_tab', 'tab-2');
    }

    /**
     * Handle the submission of PAN card details.
     */
    public function submitPan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pan_no' => 'required|string|max:255',
            'front_image_pan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('active_tab', 'tab-3');
        }

        $user = Auth::user();
        $kycDetails = KycDetail::where('user_id', $user->id)->first();

        if ($kycDetails && in_array($kycDetails->kyc_status_pan, ['submitted', 'approved'])) {
            return redirect()->back()->with('error', 'PAN details are already submitted.');
        }

        $frontImagePanPath = $kycDetails->front_image_pan;
        if ($request->hasFile('front_image_pan')) {
            if ($frontImagePanPath && Storage::disk('public')->exists($frontImagePanPath)) {
                Storage::disk('public')->delete($frontImagePanPath);
            }
            $frontImagePanPath = $request->file('front_image_pan')->store('kyc/pan', 'public');
        }

        $kycDetails->update([
            'pan_no' => $request->pan_no,
            'front_image_pan' => $frontImagePanPath,
            'kyc_status_pan' => 'submitted',
            'pan_remark' => null,
        ]);

        return redirect()->back()->with('success', 'PAN details submitted successfully.')->with('active_tab', 'tab-3');
    }

    /**
     * Handle the submission of bank details.
     */
    public function submitBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'front_image_bank' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('active_tab', 'tab-4');
        }

        $user = Auth::user();
        $kycDetails = KycDetail::where('user_id', $user->id)->first();

        if ($kycDetails && in_array($kycDetails->kyc_status_bank, ['submitted', 'approved'])) {
            return redirect()->back()->with('error', 'Bank details are already submitted.');
        }

        $frontImageBankPath = $kycDetails->front_image_bank;
        if ($request->hasFile('front_image_bank')) {
            if ($frontImageBankPath && Storage::disk('public')->exists($frontImageBankPath)) {
                Storage::disk('public')->delete($frontImageBankPath);
            }
            $frontImageBankPath = $request->file('front_image_bank')->store('kyc/bank', 'public');
        }

        $kycDetails->update([
            'account_holder_name' => $request->account_holder_name,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc_code' => $request->ifsc_code,
            'bank_branch' => $request->bank_branch,
            'front_image_bank' => $frontImageBankPath,
            'kyc_status_bank' => 'submitted',
            'bank_remark' => null,
        ]);

        return redirect()->back()->with('success', 'Bank details submitted successfully.')->with('active_tab', 'tab-4');
    }
}
