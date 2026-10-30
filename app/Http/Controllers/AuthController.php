<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserWallet;
use App\Models\User;
use App\Models\AdvanceInfo;
use App\Models\CustomField;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Hardcoded validation rules
    private $usernameMinLength = 6;
    private $passwordMinLength = 6;

    /**
     * Show registration form
     */
    public function showRegisterForm(Request $request)
    {
        $referrer = $request->query('ref');
        $RegistrationType = 'binary';

        $refSide = null;
        $refParent = null;
        $sponsorDefaultSide = null;

        if ($RegistrationType == 'binary') {
            $refSide = in_array(strtolower($request->query('side')), ['left', 'right'])
                ? strtolower($request->query('side'))
                : null;
            $refParent = $request->query('parent');
            if ($referrer) {
                $sponsor = User::where('username', $referrer)->first();
                if ($sponsor) {
                    $sponsorDefaultSide = $sponsor->default_side ?? 'left';
                }
            }
        }

        $customFields = CustomField::where('module_name', 'register')->orderBy('position')->get();
        
        $isSponsorRequired = AdvanceInfo::where('label', 'is_sponsor_required')->value('value') ?? 'no';
        $isSponsorRequired = strtolower($isSponsorRequired) === 'yes';

        $data = [
            'customFields' => $customFields,
            'referrer' => $referrer,
            'RegistrationType' => $RegistrationType,
            'isSponsorRequired' => $isSponsorRequired,
        ];

        if ($RegistrationType == 'binary') {
            $data = array_merge($data, [
                'refSide' => $refSide,
                'refParent' => $refParent,
                'sponsorDefaultSide' => $sponsorDefaultSide,
            ]);
        }

        return view('auth.register', $data);
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $RegistrationType = 'binary';
        $isSponsorRequired = 'yes';

        try {
            // --- Base validation rules ---
            $rules = [
                'title' => 'required|string|max:20',
                'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+(?: [A-Za-z]+)*$/'],
                'last_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z]+(?: [A-Za-z]+)*$/'],
                'gender' => 'nullable|in:Male,Female,Other',
                'state' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'mobile' => ['nullable', 'regex:/^[6-9][0-9]{9}$/', 'unique:users,mobile'],
                'email' => 'nullable|string|email|max:255|unique:users,email',
                'pan' => ['nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 'unique:users,pan'],
                'aadhaar' => ['nullable', 'string', 'min:12', 'max:14', 'regex:/^[0-9\s]+$/', 'unique:users,aadhaar'],
                'unique_name' => ['required', 'string', 'min:2', 'max:255', 'unique:users,unique_name'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ];

            // --- Sponsor & placement rules ---
            $rules['sponsor_display'] = $isSponsorRequired === 'yes'
                ? ['required', 'exists:users,username']
                : ['nullable', 'exists:users,username'];

            if ($RegistrationType == 'binary') {
                $rules['side'] = 'required|in:left,right';
            }

            // --- Validation ---
            $data = $request->validate($rules, [
                'password.min' => 'Password must be at least 6 characters long.',
                'unique_name.required' => 'Please enter a unique name.',
                'unique_name.min' => 'Name must be at least 2 characters long.',
                'unique_name.unique' => 'This name is already taken. Please choose another.',
                'first_name.regex' => 'First name may contain letters and spaces only (no numbers or special characters).',
                'last_name.regex' => 'Last name may contain letters and spaces only (no numbers or special characters).',
                'pan.regex' => 'Please enter a valid PAN number (e.g. ABCDE1234F).',
                'pan.unique' => 'This PAN number is already registered.',
                'aadhaar.min' => 'Aadhaar number must be exactly 12 digits (e.g. 1234 5678 9012).',
                'aadhaar.max' => 'Aadhaar number must be exactly 12 digits (e.g. 1234 5678 9012).',
                'aadhaar.regex' => 'Aadhaar number must contain only digits and spaces.',
                'aadhaar.unique' => 'This Aadhaar number is already registered.',
                'mobile.regex' => 'Mobile must be a valid 10-digit Indian number starting with 6, 7, 8 or 9.',
                'mobile.unique' => 'This mobile number is already registered.',
                'email.unique' => 'This email is already registered.',
                'sponsor_display.required' => 'Sponsor ID is required.',
                'sponsor_display.exists' => 'Sponsor not found. Please enter a valid Sponsor ID.',
                'side.required' => 'Please select a placement side.',
                'side.in' => 'Invalid placement side.',
            ]);

            // --- Data cleaning ---
            if (!empty($data['pan'])) {
                $data['pan'] = strtoupper($data['pan']);
            }
            if (!empty($data['aadhaar'])) {
                $data['aadhaar'] = preg_replace('/\s+/', '', $data['aadhaar']);
            }

            // --- Get sponsor ---
            $sponsor = null;
            if ($request->filled('sponsor_display')) {
                $sponsor = User::where('username', $request->input('sponsor_display'))->first();
                if (!$sponsor) {
                    return back()->withInput()->withErrors(['sponsor_display' => 'Sponsor not found.']);
                }
            } elseif ($isSponsorRequired === 'yes') {
                return back()->withInput()->withErrors(['sponsor_display' => 'Sponsor is required.']);
            }

            // --- Auto-generate username ---
            $username = $this->generateUsername();
            $plainPassword = $data['password'];

            // --- Use Transaction for better performance ---
            return DB::transaction(function () use ($data, $sponsor, $username, $plainPassword, $RegistrationType, $request) {
                
                // --- Prepare user data ---
                $userData = [
                    'title' => $data['title'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? null,
                    'name' => trim($data['first_name'] . ' ' . ($data['last_name'] ?? '')),
                    'gender' => $data['gender'] ?? null,
                    'state' => $data['state'] ?? null,
                    'city' => $data['city'] ?? null,
                    'mobile' => $data['mobile'] ?? null,
                    'email' => $data['email'] ?? null,
                    'pan' => $data['pan'] ?? null,
                    'aadhaar' => $data['aadhaar'] ?? null,
                    'username' => $username,
                    'unique_name' => $data['unique_name'],
                    'password' => Hash::make($plainPassword),
                    'sponsor_id' => $sponsor?->username,
                    'u_sponsor' => $sponsor?->id,
                    'role' => 'user',
                    'join_date' => now(),
                    'is_free_id' => 0,
                    'status' => 0,
                ];

                // --- Binary placement (optimized) ---
                if ($RegistrationType == 'binary') {
                    if (!$sponsor) {
                        throw ValidationException::withMessages([
                            'sponsor_display' => 'A sponsor is required for binary placement.'
                        ]);
                    }
                    
                    $sideParam = $request->input('side');
                    $parentUsername = $request->input('parent');
                    
                    if ($parentUsername) {
                        $parent = User::where('username', $parentUsername)->firstOrFail();
                        $pos = $sideParam === 'right' ? 2 : 1;
                        
                        // Check position availability with single query
                        $exists = User::where('parent_id', $parent->id)
                            ->where('position', $pos)
                            ->exists();
                            
                        if ($exists) {
                            throw ValidationException::withMessages([
                                'position' => 'This position is already occupied.'
                            ]);
                        }
                        
                        $userData['parent_id'] = $parent->id;
                        $userData['position'] = $pos;
                    } else {
                        // Find available placement quickly with optimized query
                        list($parentId, $position) = $this->findAvailablePositionOptimized($sponsor->id, $sideParam);
                        $userData['parent_id'] = $parentId;
                        $userData['position'] = $position;
                    }
                }

                // --- Create user ---
                $user = User::create($userData);

                // --- Update parent's left/right (single query) ---
                if ($RegistrationType == 'binary' && isset($userData['parent_id'])) {
                    $updateField = $userData['position'] === 1 ? 'left_id' : 'right_id';
                    User::where('id', $userData['parent_id'])->update([$updateField => $user->id]);
                }

                // --- Create wallet ---
                UserWallet::create(['u_code' => $user->id]);

                // --- Update sponsor counts (OPTIMIZED - single query for first level) ---
                if ($sponsor) {
                    // Update first level - both columns in ONE query
                    UserWallet::where('u_code', $sponsor->id)
                        ->update([
                            'c8' => DB::raw('c8 + 1'),
                            'c10' => DB::raw('c10 + 1')
                        ]);
                    
                    // Get ancestor chain (maximum 10 levels to prevent infinite loop)
                    $ancestors = $this->getAncestorsOptimized($sponsor, 10);
                    
                    // Batch update all ancestors in one go
                    if (!empty($ancestors)) {
                        UserWallet::whereIn('u_code', $ancestors)->increment('c11');
                    }
                }

                // --- Send mail after the response has been flushed ---
                // QUEUE_CONNECTION is 'sync', so ->queue() used to hand the mail
                // straight to the SMTP transport inside this request: the user
                // waited on an SSL connection to the mail host before the
                // success page rendered. Deferring it to afterResponse() keeps
                // the mail working with no queue worker while taking it off the
                // registration critical path.
                if ($user->email) {
                    $registerMail = MailTemplate::where('slug', 'registion-template')->where('status', true)->first();
                    if ($registerMail) {
                        $email = $user->email;
                        $name = $user->name;

                        dispatch(function () use ($name, $email, $plainPassword, $username) {
                            try {
                                Mail::to($email)->send(new UserRegisteredMail($name, $email, $plainPassword));
                                Log::info("Registration mail sent to {$email} with username: {$username}");
                            } catch (\Throwable $e) {
                                // Never fail a completed registration because the
                                // mail host was unreachable.
                                Log::error("Registration mail to {$email} failed: " . $e->getMessage());
                            }
                        })->afterResponse();
                    }
                }

                // Store in session for success page
                session()->flash('registration_success', [
                    'name' => $user->name,
                    'username' => $user->username,
                    'unique_name' => $user->unique_name,
                    'email' => $user->email,
                    'raw_password' => $plainPassword,
                ]);

                return redirect()->route('register.success');

            }, 5); // 5 retries for deadlock

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withInput()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * OPTIMIZED: Find available position without recursive traversal
     */
    private function findAvailablePositionOptimized($sponsorId, $side)
    {
        if ($side === 'auto' || !$side) {
            // Get count of left and right nodes using direct queries
            $leftCount = User::where('parent_id', $sponsorId)
                ->where('position', 1)
                ->count();
                
            $rightCount = User::where('parent_id', $sponsorId)
                ->where('position', 2)
                ->count();
                
            $side = $leftCount <= $rightCount ? 'left' : 'right';
        }
        
        $position = $side === 'right' ? 2 : 1;
        
        // Check if direct position is available
        $exists = User::where('parent_id', $sponsorId)
            ->where('position', $position)
            ->exists();
            
        if (!$exists) {
            return [$sponsorId, $position];
        }
        
        // Find first available position using level-based approach
        return $this->findAvailablePositionByLevel($sponsorId, $position);
    }

    /**
     * Find available position by checking levels (max 10 levels deep)
     */
    private function findAvailablePositionByLevel($rootId, $position, $maxLevel = 10)
    {
        $level = 0;
        $currentNodes = [$rootId];
        
        while ($level < $maxLevel && !empty($currentNodes)) {
            $nextLevel = [];
            
            foreach ($currentNodes as $nodeId) {
                // Check if this node has the position available
                $exists = User::where('parent_id', $nodeId)
                    ->where('position', $position)
                    ->exists();
                    
                if (!$exists) {
                    return [$nodeId, $position];
                }
                
                // Get children for next level (only 1 level deeper)
                $children = User::where('parent_id', $nodeId)
                    ->where('position', $position)
                    ->pluck('id')
                    ->toArray();
                    
                $nextLevel = array_merge($nextLevel, $children);
            }
            
            $currentNodes = $nextLevel;
            $level++;
        }
        
        // Fallback: return root with position
        return [$rootId, $position];
    }

    /**
     * OPTIMIZED: Get ancestors without recursive loop
     */
    private function getAncestorsOptimized($sponsor, $maxLevel = 10)
    {
        $ancestors = [];
        $current = $sponsor;
        $level = 0;
        
        while ($current && $level < $maxLevel) {
            if ($current->sponsor_id) {
                $parent = User::where('username', $current->sponsor_id)->first();
                if ($parent) {
                    $ancestors[] = $parent->id;
                    $current = $parent;
                } else {
                    $current = null;
                }
            } else {
                $current = null;
            }
            $level++;
        }
        
        return $ancestors;
    }

    /**
     * Generate username (cached prefix for performance)
     */
    private function generateUsername(): string
    {
        static $prefix = null;
        static $lastNumber = null;
        
        if ($prefix === null) {
            $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'NU';
        }
        
        if ($lastNumber === null) {
            $lastUser = User::where('username', 'LIKE', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
            
            $lastNumber = $lastUser 
                ? intval(substr($lastUser->username, strlen($prefix)))
                : 20000;
        }
        
        $lastNumber++;
        $username = $prefix . $lastNumber;
        
        // Quick check with caching
        while (User::where('username', $username)->exists()) {
            $lastNumber++;
            $username = $prefix . $lastNumber;
        }
        
        return $username;
    }

    /**
     * AJAX: live check for username availability.
     */
    public function checkUsername(Request $request)
    {
        $username = trim((string) $request->input('username'));
        $minLength = 6;

        if ($username === '') {
            return response()->json(['available' => false, 'message' => 'Please enter a username.']);
        }

        if (strlen($username) < $minLength) {
            return response()->json([
                'available' => false, 
                'message' => "Username must be at least $minLength characters long."
            ]);
        }

        $exists = User::where('username', $username)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This username is already taken.' : 'Username is available.',
        ]);
    }

    /**
     * AJAX: live check for unique name availability.
     */
    public function checkUniqueName(Request $request)
    {
        $uniqueName = trim((string) $request->input('unique_name'));

        if ($uniqueName === '') {
            return response()->json(['available' => false, 'message' => 'Please enter a unique name.']);
        }

        if (strlen($uniqueName) < 2) {
            return response()->json([
                'available' => false, 
                'message' => 'Name must be at least 2 characters long.'
            ]);
        }

        $exists = User::where('unique_name', $uniqueName)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This name is already taken.' : 'Name is available.',
        ]);
    }

    /**
     * AJAX: live check for PAN availability & format.
     */
    public function checkPan(Request $request)
    {
        $pan = strtoupper(trim((string) $request->input('pan')));

        if ($pan === '') {
            return response()->json(['available' => true, 'message' => '']);
        }

        if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan)) {
            return response()->json([
                'available' => false, 
                'message' => 'Invalid PAN format. Example: ABCDE1234F (5 letters, 4 numbers, 1 letter)'
            ]);
        }

        $exists = User::where('pan', $pan)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This PAN number is already registered.' : 'PAN is available.',
        ]);
    }

    /**
     * AJAX: live check for Aadhaar availability & format.
     */
    public function checkAadhaar(Request $request)
    {
        $aadhaar = trim((string) $request->input('aadhaar'));

        if ($aadhaar === '') {
            return response()->json(['available' => true, 'message' => '']);
        }

        $digitsOnly = preg_replace('/\s+/', '', $aadhaar);

        if (strlen($digitsOnly) !== 12) {
            return response()->json([
                'available' => false, 
                'message' => 'Aadhaar number must be exactly 12 digits (e.g. 1234 5678 9012).'
            ]);
        }

        if (!ctype_digit($digitsOnly)) {
            return response()->json([
                'available' => false, 
                'message' => 'Aadhaar number must contain only digits and spaces.'
            ]);
        }

        $exists = User::where('aadhaar', $digitsOnly)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This Aadhaar number is already registered.' : 'Aadhaar is available.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['auto_fill_email', 'auto_fill_password']);
        return redirect('/login');
    }

    public function impersonateLogin($id)
    {
        $target = User::findOrFail($id);

        if ($target->role === 'super admin') {
            return back()->withErrors('You can\'t impersonate another super-admin.');
        }

        session([
            'impersonator_id' => auth('superadmin')->id(),
            'impersonator_guard' => 'superadmin',
            'is_impersonating' => true,
        ]);

        match ($target->role) {
            'admin' => Auth::guard('admin')->login($target),
            'user' => Auth::guard('web')->login($target),
            default => Auth::login($target),
        };

        return match ($target->role) {
            'admin' => to_route('dashboard.admin')->with('success', 'Now impersonating admin.'),
            'user' => to_route('dashboard.user')->with('success', 'Now impersonating user.'),
            default => to_route('home')->with('success', 'Now impersonating.'),
        };
    }

    public function getSponsorName(Request $request)
    {
        $sponsorId = $request->sponsor_id;
        $user = User::where('username', $sponsorId)->first();
        if ($user) {
            return response()->json(['success' => true, 'name' => $user->name]);
        }
        return response()->json(['success' => false]);
    }

    public function getUserName(Request $request)
    {
        $sponsorId = $request->username;
        $user = User::where('username', $sponsorId)->first();
        if ($user) {
            return response()->json(['success' => true, 'name' => $user->name]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * Show registration success page with data
     */
    public function registerSuccess()
    {
        $data = session('registration_success');
        
        if ($data && !empty($data)) {
            session()->put('auto_fill_email', $data['email'] ?? null);
            session()->put('auto_fill_password', $data['raw_password'] ?? null);
            
            return view('auth.register-success', compact('data'));
        }
        
        return redirect()->route('login')->with('error', 'No registration data found. Please register again.');
    }
}