<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\CustomField;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $customFields = CustomField::where('module_name', 'register')
                                 ->orderBy('position')
                                 ->get();

        // KYC holds PAN, Aadhaar and the bank block. The profile page
        // shows them read-only rather than asking for them a second time.
        $kyc = $user->kycDetail;

        // Sponsor is shown for reference only; a member cannot change it.
        $sponsor = $user->sponsor_id
            ? User::select('id', 'title', 'first_name', 'last_name', 'name', 'username', 'mobile')
                ->where('username', $user->sponsor_id)
                ->first()
            : null;

        if ($user->role === 'super admin') {
            return view('superadmin.user_profile.edit', compact('user', 'customFields'));
        } elseif ($user->role === 'admin') {
            return view('admin.user_profile.edit', compact('user', 'customFields'));
        } else {
            return view('user.user_profile.edit', compact('user', 'customFields', 'kyc', 'sponsor'));
        }
    }

    /**
     * Update Profile Details (Name, Email, Unique Name, Custom Fields, Profile Image)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            // Letters only (optional single spaces between words); no digits.
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+(?: [A-Za-z]+)*$/'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unique_name' => 'nullable|string|min:2|max:255|unique:users,unique_name,' . $user->id,

            // --- Personal ---
            // `mobile` is deliberately absent: it is the login/OTP number and
            // is only changed by an admin. PAN, Aadhaar and the bank
            // block are not here either - they belong to KYC.
            'title' => 'nullable|string|max:20',
            // Name parts: letters only, no digits, no blank spaces.
            'first_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'last_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date|before:today',
            'marriage_anniversary' => 'nullable|date|before_or_equal:today',
            'profession' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',

            // --- Address ---
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin_code' => 'nullable|digits_between:4,10',

            // --- Extra contact numbers (digits only; Indian 10-digit mobile) ---
            'alternate_mobile' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'std_code' => 'nullable|digits_between:2,6',
            'phone_number' => 'nullable|digits_between:5,15',

            // --- Nominee ---
            'nominee_title' => 'nullable|string|max:20',
            'nominee_first_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'nominee_middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'nominee_last_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'nominee_gender' => 'nullable|in:Male,Female,Other',
            'nominee_email' => 'nullable|email|max:255',
            'nominee_mobile' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'nominee_dob' => 'nullable|date|before:today',
            'nominee_relation' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z]+(?: [A-Za-z]+)*$/'],
        ];

        $messages = [
            'name.regex' => 'Name may contain letters only (no numbers).',
            'first_name.regex' => 'First name may contain letters only, without spaces or numbers.',
            'middle_name.regex' => 'Middle name may contain letters only, without spaces or numbers.',
            'last_name.regex' => 'Last name may contain letters only, without spaces or numbers.',
            'nominee_first_name.regex' => 'Nominee first name may contain letters only, without spaces or numbers.',
            'nominee_middle_name.regex' => 'Nominee middle name may contain letters only, without spaces or numbers.',
            'nominee_last_name.regex' => 'Nominee last name may contain letters only, without spaces or numbers.',
            'nominee_relation.regex' => 'Relation may contain letters only (no numbers).',
            'alternate_mobile.regex' => 'Alternate mobile must be a valid 10-digit Indian number starting with 6, 7, 8 or 9.',
            'nominee_mobile.regex' => 'Nominee mobile must be a valid 10-digit Indian number starting with 6, 7, 8 or 9.',
            'phone_number.digits_between' => 'Phone number may contain digits only.',
            'std_code.digits_between' => 'STD code may contain digits only.',
        ];

        $customFields = CustomField::where('module_name', 'register')
            ->orderBy('position')
            ->get();

        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }

        $data = $request->validate($rules, $messages);

        // Profile image
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')
                ->storeAs('profile_images', time() . '.' . $request->file('profile_image')->extension(), 'public');
            $user->profile_image = $path;
        }

        // Update basic info
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Personal, address, extra contacts and nominee. Only keys actually
        // submitted are touched, so a partial form never wipes stored values.
        $selfEditable = [
            'title', 'first_name', 'middle_name', 'last_name', 'gender',
            'date_of_birth', 'marriage_anniversary', 'profession', 'nationality',
            'address', 'country', 'state', 'district', 'city', 'pin_code',
            'alternate_mobile', 'std_code', 'phone_number',
            'nominee_title', 'nominee_first_name', 'nominee_middle_name',
            'nominee_last_name', 'nominee_gender', 'nominee_email',
            'nominee_mobile', 'nominee_dob', 'nominee_relation',
        ];

        foreach ($selfEditable as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            $value = $data[$field];
            $user->{$field} = ($value === '' ? null : $value);
        }

        // Update Unique Name
        if ($request->filled('unique_name')) {
            $newUniqueName = trim($request->unique_name);
            
            if ($newUniqueName !== $user->unique_name) {
                $exists = User::where('unique_name', $newUniqueName)
                              ->where('id', '!=', $user->id)
                              ->exists();
                
                if ($exists) {
                    throw ValidationException::withMessages([
                        'unique_name' => 'This unique name is already taken. Please choose another.',
                    ]);
                }
                
                $user->unique_name = $newUniqueName;
            }
        }

        // Custom Fields
        $customData = [];
        
        // Check unique mobile numbers
        if ($request->has('custom')) {
            foreach ($request->input('custom') as $id => $value) {
                $field = $customFields->firstWhere('id', $id);
                if (!$field) continue;

                $fieldName = strtolower($field->name);

                if (is_array($value) && 
                    (str_contains($fieldName, 'phone') || 
                     str_contains($fieldName, 'mobile') || 
                     str_contains($fieldName, 'whatsapp'))) {
                    
                    $fullNumber = $value['full_number'] ?? (($value['country_code'] ?? '') . ($value['number'] ?? ''));

                    if ($fullNumber) {
                        $exists = User::where('id', '!=', $user->id)
                            ->whereRaw(
                                "JSON_UNQUOTE(JSON_EXTRACT(custom_fields, '$.\"{$id}\".full_number')) = ?",
                                [$fullNumber]
                            )
                            ->exists();

                        if ($exists) {
                            throw ValidationException::withMessages([
                                "custom.$id.number" => 'This mobile number is already registered.',
                            ]);
                        }
                    }
                }
            }
        }

        // Store custom fields
        if ($request->has('custom')) {
            foreach ($request->input('custom') as $id => $value) {
                $field = $customFields->firstWhere('id', $id);
                if (!$field) continue;

                $fieldName = strtolower($field->name);

                if (is_array($value) && 
                    (str_contains($fieldName, 'phone') || 
                     str_contains($fieldName, 'mobile') || 
                     str_contains($fieldName, 'whatsapp'))) {
                    
                    $customData[$id] = [
                        'number'        => $value['number'] ?? null,
                        'country_code'  => $value['country_code'] ?? null,
                        'country_name'  => $value['country_name'] ?? null,
                        'country_iso'   => $value['country_iso'] ?? null,
                        'full_number'   => $value['full_number'] ?? (($value['country_code'] ?? '') . ($value['number'] ?? '')),
                    ];
                } else {
                    $customData[$id] = is_array($value) ? implode(',', $value) : $value;
                }
            }
        }

        $user->custom_fields = $customData;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update Password Only (Separate Method)
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string|min:6',
        ];

        $data = $request->validate($rules);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}