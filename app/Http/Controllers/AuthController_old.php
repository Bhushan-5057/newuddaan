<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\CustomField;

use App\Models\UserWallet;

use App\Models\User;

use App\Models\CustomFieldValue;

use Illuminate\Support\Str;

use App\Models\AdvanceInfo;

use App\Models\MailTemplate;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegisteredMail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController_old extends Controller

{



    public function showLoginForm()

    {

        return view('auth.login'); // your login blade file

    }

    public function showRegisterForm(Request $request)
    {
        $referrer  = $request->query('ref');   // sponsor username
        $refSide   = in_array(strtolower($request->query('side')), ['left', 'right'])
            ? strtolower($request->query('side'))
            : null;
        $refParent = $request->query('parent');

        $sponsorDefaultSide = null;
        if ($referrer) {
            $sponsor = \App\Models\User::where('username', $referrer)->first();
            if ($sponsor) {
                $sponsorDefaultSide = $sponsor->default_side; // left / right / auto
            }
        }

        $countrycode    = AdvanceInfo::where('label', 'country_code')->value('value') === 'yes';
        $usernameMethod = AdvanceInfo::where('label', 'user_gen_method')->value('value') ?? 'auto';
        $customFields   = CustomField::where('module_name', 'register')->orderBy('position')->get();
        $passGenType    = AdvanceInfo::where('label', 'pass_gen_type')->value('value') ?? 'manual';

        return view('auth.register', [
            'customFields'        => $customFields,
            'countrycode'         => $countrycode,
            'usernameMethod'      => $usernameMethod,
            'passGenType'         => $passGenType,
            'referrer'            => $referrer,
            'refSide'             => $refSide,
            'refParent'           => $refParent,
            'sponsorDefaultSide'  => $sponsorDefaultSide, // 👈 sponsor nu default_side
        ]);
    }



    public function register(Request $request)
    {
        // --- Config values ---
        $usernameMethod = AdvanceInfo::where('label', 'user_gen_method')->value('value') ?? 'auto';
        $userGenFun = AdvanceInfo::where('label', 'user_gen_fun')->value('value') ?? 'alnum';
        $userGenDigit = (int)(AdvanceInfo::where('label', 'user_gen_digit')->value('value') ?? 6);
        $passGenType = AdvanceInfo::where('label', 'pass_gen_type')->value('value') ?? 'manual';
        $passGenFun = AdvanceInfo::where('label', 'pass_gen_fun')->value('value') ?? 'alnum';
        $passGenDigit = (int)(AdvanceInfo::where('label', 'pass_gen_digit')->value('value') ?? 8);
        $isSponsorRequired = AdvanceInfo::where('label', 'is_sponsor_required')->value('value') ?? 'no';
        $onlyInactiveSponsor = AdvanceInfo::where('label', 'only_inactive_sponsor')->value('value') ?? 'no';



        // --- Base validation rules ---
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'side' => 'required|in:left,right,auto',
        ];

        // Password rules
        if ($passGenType === 'manual') {
            $patterns = [
                'alpha' => 'regex:/^[a-zA-Z]+$/',
                'numeric' => 'regex:/^[0-9]+$/',
                'alnum' => 'regex:/^[a-zA-Z0-9]+$/',
            ];
            $passwordPattern = $patterns[$passGenFun] ?? $patterns['alnum'];
            $rules['password'] = [
                'required',
                'string',
                "min:$passGenDigit",
                'confirmed',
                $passwordPattern,
            ];
        }

        // Username rules
        if ($usernameMethod === 'manual') {
            $patterns = [
                'alpha' => 'regex:/^[a-zA-Z]+$/',
                'numeric' => 'regex:/^[0-9]+$/',
                'alnum' => 'regex:/^[a-zA-Z0-9]+$/',
            ];
            $patternRule = $patterns[$userGenFun] ?? $patterns['alnum'];
            $rules['username'] = [
                'required',
                'string',
                "min:$userGenDigit",
                'max:255',
                'unique:users,username',
                $patternRule,
            ];
        }
        // Sponsor rule
        $rules['sponsor_display'] = strtolower($isSponsorRequired) === 'yes'
            ? ['required', 'exists:users,username']
            : ['nullable', 'exists:users,username'];





        $customFields = CustomField::where('module_name', 'register')->orderBy('position')->get();
        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }
        // --- Email limit ---
        $emaillimit = AdvanceInfo::where('label', 'email_users')->value('value') ?? 100;
        if (User::where('email', $request->input('email'))->count() >= $emaillimit) {
            return back()->withInput()->withErrors(['email' => 'This email has reached the maximum number of allowed registrations.']);
        }

        // --- Mobile / Phone / WhatsApp field limit ---
        $mobileLimit = AdvanceInfo::where('label', 'mobile_users')->value('value') ?? 100;


        $phoneFields = $customFields->filter(
            fn($f) =>
            str_contains(strtolower($f->name), 'phone') ||
            str_contains(strtolower($f->name), 'mobile') ||
            str_contains(strtolower($f->name), 'whatsapp')
        );

        foreach ($phoneFields as $field) {

            $fieldId = $field->id;

            // custom[fieldId] is ARRAY
            $phoneData = $request->input("custom.$fieldId");

            // âœ… Safe check
            if (is_array($phoneData) && !empty($phoneData['full_number'])) {

                $fullNumber = $phoneData['full_number'];

                // JSON search inside users.custom_fields
       

                $count = User::whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(custom_fields, '$.\"$fieldId\".full_number')) = ?",
                    [$fullNumber]
                )->count();

                if ($count >= $mobileLimit) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'custom' => 'This phone number has reached the registration limit.'
                        ]);
                }
            }
        }


        // --- Aadhaar & PAN field limits ---
        $aadhaarLimit = AdvanceInfo::where('label', 'adhar_users')->value('value') ?? 1;
        $panLimit = AdvanceInfo::where('label', 'pan_users')->value('value') ?? 1;

        $aadhaarFields = $customFields->filter(fn($f) => str_contains(strtolower($f->name), 'adhar card'));
        $panFields = $customFields->filter(fn($f) => str_contains(strtolower($f->name), 'pan card'));


        foreach ($aadhaarFields as $field) {
            $fieldId = $field->id;
            $value = strtoupper($request->input("custom.$fieldId"));
            if ($value && User::where("custom_fields->{$fieldId}", $value)->count() >= $aadhaarLimit) {
                return back()->withInput()->withErrors(['custom' => 'This Aadhaar number has reached the registration limit.']);
            }
        }

        foreach ($panFields as $field) {
            $fieldId = $field->id;
            $value = strtoupper($request->input("custom.$fieldId"));
            if ($value && User::where("custom_fields->{$fieldId}", $value)->count() >= $panLimit) {
                return back()->withInput()->withErrors(['custom' => 'This PAN number has reached the registration limit.']);
            }
        }






        // --- Validate ---
        $data = $request->validate($rules, [
            'username.regex' => match ($userGenFun) {
                'alpha' => 'Username must contain only alphabets.',
                'numeric' => 'Username must contain only numbers.',
                default => 'Username must contain only letters and numbers.',
            },
            'username.min' => "Username must be at least $userGenDigit characters long.",
            'password.regex' => match ($passGenFun) {
                'alpha' => 'Password must contain only alphabets.',
                'numeric' => 'Password must contain only numbers.',
                default => 'Password must contain only letters and numbers.',
            },
            'password.min' => "Password must be at least $passGenDigit characters long.",
        ]);

        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $data->errors()->all()
            ], 422);
        }


        dd($request->all());





        // --- Sponsor active/inactive check ---
        if ($request->filled('sponsor_display')) {
            $sponsorUsername = $request->input('sponsor_display');
            $sponsorUser = User::where('username', $sponsorUsername)->first();

            if (!$sponsorUser) {
                return back()->withInput()->withErrors(['sponsor_display' => 'Sponsor not found.']);
            }

            $wallet = UserWallet::where('u_code', $sponsorUser->id)->first();

            if (!$wallet) {
                return back()->withInput()->withErrors(['sponsor_display' => 'Sponsor wallet not found.']);
            }

            $isSponsorActive = $sponsorUser->status === 1;

            if ($onlyInactiveSponsor === 'no' && !$isSponsorActive) {
                return back()->withInput()->withErrors(['sponsor_display' => 'Sponsor is inactive. Please use an active sponsor.']);
            }
        }

        // --- Generate password ---
        if ($passGenType === 'auto') {
            $charSet = match ($passGenFun) {
                'alpha' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'numeric' => '0123456789',
                default => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            };
            $plainPassword = '';
            for ($i = 0; $i < $passGenDigit; $i++) {
                $plainPassword .= $charSet[random_int(0, strlen($charSet) - 1)];
            }
        } else {
            $plainPassword = $data['password'];
        }

        // --- Generate username ---
        $username = $usernameMethod === 'manual'
            ? $data['username']
            : (AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER') . mt_rand(
                (int)str_pad('1', $userGenDigit, '0'),
                (int)str_pad('', $userGenDigit, '9')
            );


        DB::transaction(function () use ($data, $user, $request, $username, $plainPassword) {
            // 1) Sponsor (username in ?ref or from $data['sponsor_display'])
            $sponsorUsername = $request->input('ref', $data['sponsor_display'] ?? null);
            if (!$sponsorUsername) {
                throw new \Exception('Sponsor (ref) is required.');
            }

            $sponsor = User::where('username', $sponsorUsername)->first();
            if (!$sponsor) {
                throw new \Exception('Sponsor not found.');
            }

            // 2) Resolve placement from (parent username?, side?)
            $parentUsername = $request->input('parent');                 // may be null
            $sideParam      = $request->input('side');                   // "left" | "right" | null



            $placement = $this->resolvePlacement($sponsor, $sideParam, $parentUsername);

            // 3) Create user
            $user = User::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'username'    => $username,
                'password'    => Hash::make($plainPassword),
                'sponsor_id'  => $sponsor->username,
                'u_sponsor'  =>   $sponsor->id,
                'parent_id'   => $placement['parent']->id,
                'position'    => $placement['position'], 
                'left_id'     => null,
                'right_id'    => null,
                'left_count'  => 0,
                'right_count' => 0,
                'join_date' => now(),
            ]);

            // 4) Update parent’s left/right pointer

            if ($placement['position'] === 1) {
                $placement['parent']->update(['left_id' => $user->id]);
            } else {
                $placement['parent']->update(['right_id' => $user->id]);
            }





            // --- Custom fields ---


            $fieldIds = [];
            $fieldValues = [];

            if (isset($data['custom'])) {

                foreach ($data['custom'] as $fieldId => $value) {
                    $customFields = CustomField::where('module_name', 'register')->orderBy('position')->get();
                    $field = $customFields->firstWhere('id', $fieldId);
                    if (!$field) continue;

                    // ðŸ“± Phone / Mobile / WhatsApp field
                    if (is_array($value) && isset($value['number'])) {

                        $number = $value['number'] ?? null;
                        $code   = $value['country_code'] ?? null;
                        $name   = $value['country_name'] ?? null;
                        $iso    = $value['country_iso'] ?? null;

                        $storedValue = [
                            'number'        => $number,
                            'country_code'  => $code,
                            'country_name'  => $name,
                            'country_iso'   => $iso,
                            'full_number'   => ($code && $number) ? $code . $number : null,
                        ];
                    } else {
                        // ðŸ§¾ Normal custom field (text, select, checkbox, etc.)
                        $storedValue = $value;
                    }

                    $fieldIds[] = $fieldId;
                    $fieldValues[$fieldId] = $storedValue;
                }

                CustomFieldValue::create([
                    'user_id' => $user->id,
                    'custom_field_ids' => json_encode($fieldIds),
                    'value' => json_encode($fieldValues, JSON_UNESCAPED_UNICODE),
                    'module_name' => 'register',
                ]);
            }

            // Save also in users table if needed
            $user->custom_fields = $fieldValues;
            $user->save();


            // --- Wallets ---
            UserWallet::create(['u_code' => $user->id]);



            // --- Upline counts ---
            $currentSponsor = User::where('username', $sponsor->username)->first();

            $RegistrationType = AdvanceInfo::where('label', 'reg_type')->value('value');
            if ($RegistrationType == 'binary') {
                $this->updateBinary($sponsor);
            }

            if ($currentSponsor) {
                $directWallet = UserWallet::firstOrNew(['u_code' => $currentSponsor->id]);
                $directWallet->c8 = ($directWallet->c8 ?? 0) + 1;
                $directWallet->c10 = ($directWallet->c10 ?? 0) + 1;
                $directWallet->save();
                while ($currentSponsor) {
                    $wallet = UserWallet::firstOrNew(['u_code' => $currentSponsor->id]);
                    $wallet->c11 = ($wallet->c11 ?? 0) + 1;
                    $wallet->save();
                    $currentSponsor = $currentSponsor->sponsor_id
                        ? User::where('username', $currentSponsor->sponsor_id)->first()
                        : null;
                }
            }

            // --- Send registration mail ---
            $registerMail = MailTemplate::where('slug', 'registion-template')->where('status', true)->first();
            if ($registerMail) {
                Mail::to($user->email)->send(new UserRegisteredMail($user->name, $user->email, $plainPassword));
                Log::info("Mail sent to {$user->email} with username: {$username} and password: {$plainPassword}");
            }
        });

        return redirect()->route('register.success')->with([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'raw_password' => $plainPassword, // Only use this if password is plain and not encrypted
        ]);
    }




    /**
     * Resolve final placement parent + position (1=left,2=right)
     * - If $parentUsername present:
     *     - If $side given -> ensure available
     *     - If $side absent -> pick first free side (left then right)
     * - If no $parentUsername:
     *     - If $side given -> BFS under sponsor on that side
     *     - If no $side -> try left BFS then right BFS under sponsor
     */
    /**
     * Placement resolution
     */
    private function resolvePlacement(User $sponsor, ?string $side, ?string $parentUsername): array
    {
        $side = $side ? strtolower($side) : null;

        // Parent given
        if ($parentUsername) {
            $parent = User::where('username', $parentUsername)->firstOrFail();

            if ($side === 'auto') {
                $leftCount  = count($this->teamByPosition($parent->id, 1));
                $rightCount = count($this->teamByPosition($parent->id, 2));
                $side = $leftCount <= $rightCount ? 'left' : 'right';
            }

            $pos = $side === 'right' ? 2 : 1;
            $this->checkPositionAvailable($parent->id, $pos);

            return ['parent' => $parent, 'position' => $pos];
        }

        // No parent → sponsor
        if ($side === 'auto') {
            $leftCount  = count($this->teamByPosition($sponsor->id, 1));
            $rightCount = count($this->teamByPosition($sponsor->id, 2));
            $side = $leftCount <= $rightCount ? 'left' : 'right';
        }

        $pos = $side === 'right' ? 2 : 1;
        return $this->findPlacement($sponsor, $side ?? 'left', $pos);
    }

    private function checkPositionAvailable(int $parentId, int $position): void
    {
        $exists = User::where('parent_id', $parentId)->where('position', $position)->exists();
        if ($exists) {
            throw new \Exception("This position already occupied.");
        }
    }

    private function teamByPosition(int $userId, int $position): array
    {
        $child = User::where('parent_id', $userId)->where('position', $position)->first();
        if (!$child) return [];
        $downlines = $this->myBinary($child->id);
        $downlines[] = $child->id;
        return $downlines;
    }

    private function myBinary(int $userId): array
    {
        $arrin = [$userId];
        $ret = [];

        while (!empty($arrin)) {
            $children = User::whereIn('parent_id', $arrin)->pluck('id')->toArray();
            if (!empty($children)) {
                $ret = array_merge($ret, $children);
                $arrin = $children;
            } else {
                $arrin = [];
            }
        }
        return $ret;
    }

    private function updateBinary(?User $user): void
    {
        if (!$user) return;
        $wallet = UserWallet::firstOrNew(['u_code' => $user->id]);
        $wallet->c13 = count($this->teamByPosition($user->id, 1));
        $wallet->c14 = count($this->teamByPosition($user->id, 2));
        $wallet->save();

        if ($user->parent_id) {
            $this->updateBinary(User::find($user->parent_id));
        }
    }



    public function logout()

    {

        Auth::logout();



        return redirect('/login');
    }

    public function impersonateLogin($id)
    {
        // Only super‑admins may impersonate

        // Target account
        $target = User::findOrFail($id);

        // Don’t allow impersonating another super‑admin
        if ($target->role === 'super admin') {
            return back()->withErrors('You can’t impersonate another super‑admin.');
        }

        /* -----------------------------------------
     |  1. Store who is impersonating & guard  |
     *----------------------------------------*/
        session([
            'impersonator_id'   => auth('superadmin')->id(),   // original SA id
            'impersonator_guard' => 'superadmin',               // to restore later
            'is_impersonating'  => true,
        ]);

        /* -----------------------------------------
     |  2. Log in with the proper guard        |
     *----------------------------------------*/
        match ($target->role) {
            'admin' => Auth::guard('admin')->login($target),
            'user'  => Auth::guard('web')->login($target),
            default => Auth::login($target), // fallback to default guard
        };

        /* -----------------------------------------
     |  3. Redirect to that role’s dashboard   |
     *----------------------------------------*/
        return match ($target->role) {
            'admin' => to_route('dashboard.admin')->with('success', 'Now impersonating admin.'),
            'user'  => to_route('dashboard.user')->with('success', 'Now impersonating user.'),
            default => to_route('home')->with('success', 'Now impersonating.'),
        };
    }



    public function getSponsorName(Request $request)
    {
        $sponsorId = $request->sponsor_id;

        $user = User::where('username', $sponsorId)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'name' => $user->name,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
