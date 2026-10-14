<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\CustomField;
use App\Models\AdvanceInfo;
use App\Models\CustomFieldValue;
use Illuminate\Support\Str;
use App\Models\MailTemplate;
use App\Models\PinDetail;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use Carbon\Carbon;
use App\Mail\UserRegisteredMail;

class UserController extends Controller
{
    public function userprofile(Request $request)
    {
        $user = Auth::user();

        // KYC-owned details (PAN, Aadhaar, address, bank) are displayed from
        // kyc_details rather than duplicated onto the user record.
        $kyc = $user->kycDetail;

        $sponsor = $user->sponsor_id
            ? User::select('id', 'title', 'first_name', 'last_name', 'name', 'username', 'mobile')
                ->where('username', $user->sponsor_id)
                ->first()
            : null;

        $customFields = CustomField::where('module_name', 'register')
            ->orderBy('position')
            ->get();

        return view('user.user_profile.index', compact('user', 'kyc', 'sponsor', 'customFields'));
    }
    public function updateDefaultSide(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'default_side' => 'required|in:left,right,auto',
        ]);
        // ✅ Update default side for logged-in user
        $user = auth()->user();
        $user->default_side = $validated['default_side'];
        $user->save();
        return redirect()
            ->back()
            ->with('success', 'Default side updated to ' . ucfirst($validated['default_side']) . '.');
    }
    // admin
    public function adminindex(Request $request)
    {
        // These counters used to pluck every matching user id into PHP and feed
        // the whole list back as whereIn(...) - thousands of placeholders per
        // page load. A join against the same condition does it in one pass.
        $totalUsers = User::where('role', 'user')->count();

        $lastWeekUsers = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        $walletCountByStatus = function (int $status, bool $activated) {
            return UserWallet::join('users', 'users.id', '=', 'user_wallets.u_code')
                ->where('users.role', 'user')
                ->where('users.status', $status)
                ->when($activated,
                    fn ($q) => $q->where('user_wallets.active_id', '!=', 0),
                    fn ($q) => $q->where('user_wallets.active_id', 0)
                )
                ->count();
        };

        $activeUsers = $walletCountByStatus(1, true);
        $pendingUsers = $walletCountByStatus(0, false);

        return view('admin.users.viewlist', compact(
            'totalUsers',
            'lastWeekUsers',
            'activeUsers',
            'pendingUsers'
        ));
    }
public function admingetData(Request $request)
{
    try {
        $currentUser = auth()->user();
        $baseQuery = User::with('sponsor')
            ->where('role', 'user')
            ->where('id', '!=', $currentUser->id);

        // Filters
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $baseQuery->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($search = $request->input('search.value')) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $totalCount = User::where('role', 'user')->where('id', '!=', $currentUser->id)->count();
        $filteredCount = $baseQuery->count();

        $users = $baseQuery->offset($request->start)
            ->limit($request->length)
            ->orderBy('id', 'asc')
            ->get();

        // --- OPTIMIZATION: Get all package totals in one go ---
        $userIds = $users->pluck('id');
        $packageTotals = Order::whereIn('tx_user_id', $userIds)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->groupBy('tx_user_id')
            ->selectRaw('tx_user_id, SUM(order_amount) as total')
            ->pluck('total', 'tx_user_id');
        // -----------------------------------------------------

        $data = [];
        foreach ($users as $index => $user) {
            $row = [];
            $row[] = '<input type="checkbox" class="row-checkbox" value="' . $user->id . '">';
            $row[] = $request->start + $index + 1;

            // Action Buttons
            $actionButtons = '';
            if (company_info('Users show Login', 'no') === 'yes') {
                $actionButtons .= '<form method="POST" action="' . route('admin.impersonate.login', $user->id) . '" style="display:inline;" target="_blank">' . csrf_field() . '<button type="submit" class="btn btn-sm btn-warning me-1">Login</button></form>';
            }
            $actionButtons .= '<button type="button" class="btn btn-sm btn-info me-1 text-white btn-view" data-id="' . $user->id . '">View</button>';
            if ($currentUser->hasPermission('user', 'can_edit')) {
                $actionButtons .= '<button class="btn btn-sm btn-primary btn-edit" data-id="' . $user->id . '">Edit</button>';
            }
            $row[] = $actionButtons;

            if (Advance_info('register_name', 'yes') === 'yes') $row[] = e($user->name);
            if (Advance_info('register_wallets', 'no') === 'yes') $row[] = e($user->wallet_address ?? 'N/A');
            
            $row[] = e($user->username);
            
            if (Advance_info('register_email', 'yes') === 'yes') $row[] = e($user->email);

            $row[] = $user->sponsor ? e($user->sponsor->name) . ' (' . $user->sponsor->username . ')' : 'Admin';

        // ✅ Status badge (Updated for compatibility)
            $status = (int) $user->status; // Cast to integer to ensure strict match works
            $statusBadge = match ($status) {
                1 => '<span class="badge bg-success">Active</span>',
                0 => '<span class="badge bg-secondary">Inactive</span>',
                2 => '<span class="badge bg-danger">Blocked</span>',
                default => '<span class="badge bg-dark">Unknown</span>', 
            };
            $row[] = $statusBadge;
            
            

            // Use the pre-calculated total
            $totalAmount = $packageTotals[$user->id] ?? 0;
            $row[] = company_info('currency', '$') . ' ' . number_format($totalAmount, 2);
            $row[] = $user->created_at->format('Y-m-d');

            $data[] = $row;
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $filteredCount,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        // This will help you see the error in the Network Tab
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    public function exportUsersCsv(Request $request)
    {
        $ids = $request->ids ? explode(',', $request->ids) : [];

        $filename = 'users_list_' . date('Ymd_His') . '.csv';

        $users = User::with('sponsor')
            ->where('role', 'user')
            ->when(count($ids), fn($q) => $q->whereIn('id', $ids))
            ->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($users) {

            $file = fopen('php://output', 'w');

            /*
        |--------------------------------------------------------------------------
        | 1. CSV HEADER (YES / NO)
        |--------------------------------------------------------------------------
        */
            $header = ['S No'];

            if (Advance_info('register_name', 'yes') === 'yes') {
                $header[] = 'Name';
            }

            if (Advance_info('register_wallets', 'no') === 'yes') {
                $header[] = 'Wallet';
            }

            $header[] = 'Username';

            if (Advance_info('register_email', 'yes') === 'yes') {
                $header[] = 'Email';
            }

            $header[] = 'Sponsor';
            $header[] = 'Status';
            $header[] = 'Package';
            $header[] = 'Registered';

            fputcsv($file, $header);

            /*
        |--------------------------------------------------------------------------
        | 2. CSV ROWS (MATCHING SAME ORDER)
        |--------------------------------------------------------------------------
        */
            foreach ($users as $index => $user) {

                $row = [];
                $row[] = $index + 1;

                if (Advance_info('register_name', 'yes') === 'yes') {
                    $row[] = $user->name;
                }

                if (Advance_info('register_wallets', 'no') === 'yes') {
                    $row[] = $user->wallet_address ?? 'N/A';
                }

                $row[] = $user->username;

                if (Advance_info('register_email', 'yes') === 'yes') {
                    $row[] = $user->email;
                }

                // Sponsor
                $row[] = $user->sponsor
                    ? $user->sponsor->name . ' (' . $user->sponsor->username . ')'
                    : 'Admin';

                // Status
                $row[] = match ($user->status) {
                    1 => 'Active',
                    0 => 'Inactive',
                    2 => 'Blocked',
                    default => 'Unknown',
                };

                // Package total
                $totalPackage = Order::where('tx_user_id', $user->id)
                    ->whereIn('tx_type', ['topup', 'retopup'])
                    ->sum('order_amount');

                $row[] = number_format($totalPackage, 2);
                $row[] = $user->created_at->format('Y-m-d H:i:s');

                fputcsv($file, $row);
            }

            fclose($file);
        }, 200, $headers);
    }


    public function admincreate(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | 1. Dynamic Validation Rules (YES / NO based)
    |--------------------------------------------------------------------------
    */
        $rules = [
            'role' => 'required|string',
        ];

        if (Advance_info('register_name', 'yes') === 'yes') {
            $rules['name'] = 'required|string|max:255';
        }

        if (Advance_info('register_email', 'yes') === 'yes') {
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
        }

        if (Advance_info('register_password', 'yes') === 'yes') {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        if (Advance_info('register_wallets', 'no') === 'yes') {
            $rules['wallet_address'] = 'required|string|max:255';
        }

        /*
    |--------------------------------------------------------------------------
    | 2. Custom Fields Validation
    |--------------------------------------------------------------------------
    */
        $customFields = CustomField::where('module_name', 'register')
            ->orderBy('position')
            ->get();

        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }

        $data = $request->validate($rules);

        /*
    |--------------------------------------------------------------------------
    | 3. Username Generation
    |--------------------------------------------------------------------------
    */
        $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
        $username = $prefix . mt_rand(10000000, 99999999);

        /*
    |--------------------------------------------------------------------------
    | 4. Prepare User Data (ONLY enabled fields)
    |--------------------------------------------------------------------------
    */
        $userData = [
            'username' => $username,
            'role'     => $data['role'],
        ];

        if (Advance_info('register_name', 'yes') === 'yes') {
            $userData['name'] = $data['name'];
        }

        if (Advance_info('register_email', 'yes') === 'yes') {
            $userData['email'] = $data['email'];
        }

        if (Advance_info('register_password', 'yes') === 'yes') {
            $plainPassword = $data['password'];
            $userData['password'] = Hash::make($plainPassword);
        } else {
            $plainPassword = null;
        }

        if (Advance_info('register_wallets', 'no') === 'yes') {
            $userData['wallet_address'] = $data['wallet_address'];
        }

        /*
    |--------------------------------------------------------------------------
    | 5. Create User
    |--------------------------------------------------------------------------
    */
        $user = User::create($userData);

        /*
    |--------------------------------------------------------------------------
    | 6. Save Custom Fields
    |--------------------------------------------------------------------------
    */
        $fieldIds = [];
        $fieldValues = [];

        if (isset($data['custom'])) {
            foreach ($data['custom'] as $fieldId => $value) {
                $field = $customFields->where('id', $fieldId)->first();
                if (!$field) continue;

                $storedValue = is_array($value) ? implode(',', $value) : $value;

                // Country code merge for phone fields
                $countryCode = $request->input("country_code.$fieldId");
                if (
                    $countryCode &&
                    (str_contains(strtolower($field->name), 'phone') ||
                        str_contains(strtolower($field->name), 'mobile') ||
                        str_contains(strtolower($field->name), 'whatsapp'))
                ) {
                    $storedValue = $countryCode . $storedValue;
                }

                $fieldIds[] = $fieldId;
                $fieldValues[$fieldId] = $storedValue;
            }

            CustomFieldValue::create([
                'user_id'          => $user->id,
                'custom_field_ids' => json_encode($fieldIds),
                'value'            => json_encode($fieldValues),
                'module_name'      => 'register',
            ]);
        }

        // Store custom fields in user table (optional)
        $user->custom_fields = $fieldValues;
        $user->save();

        /*
    |--------------------------------------------------------------------------
    | 7. Send Welcome Mail (only if enabled)
    |--------------------------------------------------------------------------
    */
        if (
            Advance_info('register_email', 'yes') === 'yes' &&
            Advance_info('register_password', 'yes') === 'yes'
        ) {
            $registerMail = MailTemplate::where('slug', 'registion-template')
                ->where('status', true)
                ->first();

            if ($registerMail) {
                Mail::to($user->email)
                    ->send(new UserRegisteredMail(
                        $user->email,
                        $plainPassword,
                        $username
                    ));
            }
        }

        /*
    |--------------------------------------------------------------------------
    | 8. Redirect
    |--------------------------------------------------------------------------
    */
        return redirect()->back()->with('success', 'User created successfully.');
    }

    /**
     * Fields captured on the registration form that an admin may also edit.
     * Kept in one place so adminedit(), adminupdate() and adminshow() stay in
     * step with the register form.
     */
    private const REGISTRATION_FIELDS = [
        'title',
        'first_name',
        'last_name',
        'gender',
        'state',
        'city',
        'mobile',
        'pan',
        'aadhaar',
        'unique_name',
    ];

    public function adminedit($id)
    {
        $user = User::findOrFail($id);

        $payload = [
            'id'             => $user->id,
            'name'           => Advance_info('register_name', 'yes') === 'yes' ? $user->name : null,
            'email'          => Advance_info('register_email', 'yes') === 'yes' ? $user->email : null,
            'wallet_address' => Advance_info('register_wallets', 'no') === 'yes' ? $user->wallet_address : null,
            'role'           => $user->role,
            'status'         => $user->status,
            'custom_fields'  => $user->custom_fields ?? [],
        ];

        // Everything else the registration form collects.
        foreach (self::REGISTRATION_FIELDS as $field) {
            $payload[$field] = $user->{$field};
        }

        return response()->json($payload);
    }


    public function adminupdate(Request $request, $id)
    {
        /*
    |--------------------------------------------------------------------------
    | 1. Dynamic validation (YES / NO)
    |--------------------------------------------------------------------------
    */
        $rules = [
            'role'   => 'required|string',
            'status' => 'required|in:0,1,2',
        ];

        if (Advance_info('register_name', 'yes') === 'yes') {
            $rules['name'] = 'required|string|max:255';
        }

        if (Advance_info('register_wallets', 'no') === 'yes') {
            $rules['wallet_address'] = [
                'required',
                'regex:/^0x[a-fA-F0-9]{40}$/'
            ];
        }

        if (Advance_info('register_email', 'yes') === 'yes') {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $id;
        }

        if (Advance_info('register_password', 'yes') === 'yes') {
            $rules['password'] = 'nullable|min:6|confirmed';
            $rules['password_confirmation'] = 'nullable|min:6';
        }

        // Registration-form fields. Same rules the public form applies, with
        // the uniqueness checks scoped to ignore this user's own row.
        $rules['title']       = 'nullable|string|max:20';
        $rules['first_name']  = 'nullable|string|max:255';
        $rules['last_name']   = 'nullable|string|max:255';
        $rules['gender']      = 'nullable|in:Male,Female,Other';
        $rules['state']       = 'nullable|string|max:100';
        $rules['city']        = 'nullable|string|max:100';
        $rules['mobile']      = 'nullable|digits_between:8,15|unique:users,mobile,' . $id;
        $rules['pan']         = ['nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 'unique:users,pan,' . $id];
        $rules['aadhaar']     = ['nullable', 'string', 'min:12', 'max:14', 'regex:/^[0-9\s]+$/', 'unique:users,aadhaar,' . $id];
        $rules['unique_name'] = 'nullable|string|min:2|max:255|unique:users,unique_name,' . $id;

        // Custom fields
        $customFields = CustomField::where('module_name', 'register')
            ->orderBy('position')
            ->get();

        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }

        // Normalise before validating: the PAN rule requires upper case and the
        // Aadhaar rule rejects spaces, but both are natural things for an admin
        // to type. Register does the same tidy-up, just after validation.
        if ($request->filled('pan')) {
            $request->merge(['pan' => strtoupper(trim($request->input('pan')))]);
        }
        if ($request->filled('aadhaar')) {
            $request->merge(['aadhaar' => preg_replace('/\s+/', '', $request->input('aadhaar'))]);
        }

        $data = $request->validate($rules);

        /*
    |--------------------------------------------------------------------------
    | 2. Update User
    |--------------------------------------------------------------------------
    */
        $user = User::findOrFail($id);

        if (Advance_info('register_name', 'yes') === 'yes') {
            $user->name = $data['name'];
        }

        if (Advance_info('register_email', 'yes') === 'yes') {
            $user->email = $data['email'];
        }

        if (Advance_info('register_wallets', 'no') === 'yes') {
            $user->wallet_address = $data['wallet_address'];
        }

        // Registration-form fields. Only keys actually submitted are touched,
        // so a partial form never blanks out stored data.
        foreach (self::REGISTRATION_FIELDS as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];

            if ($field === 'pan' && !empty($value)) {
                $value = strtoupper($value);
            }
            if ($field === 'aadhaar' && !empty($value)) {
                $value = preg_replace('/\s+/', '', $value);
            }

            $user->{$field} = $value === '' ? null : $value;
        }

        // Register composes `name` from the two name parts. The admin form has
        // its own Name input, so only fall back to composing when that input
        // was not submitted - otherwise an explicit edit would be overwritten.
        if (
            !array_key_exists('name', $data)
            && (array_key_exists('first_name', $data) || array_key_exists('last_name', $data))
        ) {
            $composed = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if ($composed !== '') {
                $user->name = $composed;
            }
        }

        $user->role   = $data['role'];
        $user->status = $data['status'];

        // Update password only if provided
        if (
            Advance_info('register_password', 'yes') === 'yes' &&
            $request->filled('password')
        ) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        /*
    |--------------------------------------------------------------------------
    | 3. Custom Fields Update
    |--------------------------------------------------------------------------
    */
        $fieldIds = [];
        $fieldValues = [];

        if (isset($data['custom'])) {
            foreach ($data['custom'] as $fieldId => $value) {
                $field = $customFields->where('id', $fieldId)->first();
                if (!$field) continue;

                $storedValue = is_array($value) ? implode(',', $value) : $value;

                $countryCode = $request->input("country_code.$fieldId");
                if ($countryCode && str_contains(strtolower($field->name), 'phone')) {
                    $storedValue = $countryCode . $storedValue;
                }

                $fieldIds[] = $fieldId;
                $fieldValues[$fieldId] = $storedValue;
            }

            CustomFieldValue::updateOrCreate(
                ['user_id' => $user->id, 'module_name' => 'register'],
                [
                    'custom_field_ids' => json_encode($fieldIds),
                    'value'            => json_encode($fieldValues),
                ]
            );

            $user->custom_fields = $fieldValues;
            $user->save();
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }


    public function admindestroy($id)
    {
        $user = User::findOrFail($id);
        // $user->delete();
        return response()->json(['success' => true]);
    }
    /**
     * Read-only detail for one user.
     *
     * Returns just the detail fragment - the admin user list drops it into a
     * modal, so there is no separate page to navigate to.
     */
    public function adminshow($id)
    {
        $user = User::with(['sponsor', 'wallet', 'kycDetail'])->findOrFail($id);

        $customFields = CustomField::where('module_name', 'register')
            ->orderBy('position')
            ->get();

        $packageTotal = (float) Order::where('tx_user_id', $user->id)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->where('status', 1)
            ->sum('order_amount');

        $placementParent = $user->parent_id
            ? User::select('id', 'name', 'username')->find($user->parent_id)
            : null;

        return view('admin.users._detail', compact(
            'user',
            'customFields',
            'packageTotal',
            'placementParent'
        ));
    }

    public function adminprofile(Request $request)
    {
        $user = Auth::user();
        return view('admin.user_profile.index', compact('user'));
    }


    //    superadmin
    public function superadminindex(Request $request)
    {
        $activeusers = User::where('role', 'user')->where('status', 1);
        $activeuserIds = $activeusers->pluck('id');
        $pendingusers = User::where('role', 'user')->where('status', 0);
        $pendinguserIds = $pendingusers->pluck('id');
        $users = User::where('role', 'user');
        $userIds = $users->pluck('id');
        $totalUsers = $users->count();
        $lastWeekUsers = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $activeUsers = UserWallet::whereIn('u_code', $activeuserIds)->where('active_id', '!=', 0)->count();
        $pendingUsers = UserWallet::whereIn('u_code', $pendinguserIds)->where('active_id', 0)->count();
        return view('superadmin.users.viewlist', compact(
            'totalUsers',
            'lastWeekUsers',
            'activeUsers',
            'pendingUsers'
        ));
    }
    public function superadmingetData(Request $request)
    {
        $columns = ['id', 'name', 'username', 'email', 'role', 'sponsor_id', 'active_status', 'package', 'created_at'];
        $length = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');
        $orderColumn = $columns[$request->input('order.0.column')];
        $orderDir = $request->input('order.0.dir');
        $currentUser = auth()->user();
        $query = User::with('sponsor') // ✅ include sponsor relationship
            ->where('id', '!=', $currentUser->id);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%");
            });
        }
        $total = $query->count();
        $users = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();
        $loggedInUserId = auth()->id();
        $data = [];
        foreach ($users as $index => $user) {
            $loginButton = '';
            if ($user->id != $loggedInUserId) {
                $loginButton = '
                <form method="POST" action="' . route('impersonate.login', $user->id) . '" style="display:inline;" target="_blank">
                    ' . csrf_field() . '
                    <button type="submit" class="btn btn-sm btn-warning mt-1 mb-1">Login</button>
                </form>
            ';
            }
            // ✅ Show active/inactive/blocked status for users
            $statusHtml = '';
            if ($user->role === 'user') {
                switch ($user->status) {
                    case 1:
                        $activeStatus = 'Active';
                        $badgeClass = 'success';
                        break;
                    case 0:
                        $activeStatus = 'Inactive';
                        $badgeClass = 'secondary';
                        break;
                    case 2:
                        $activeStatus = 'Blocked';
                        $badgeClass = 'danger';
                        break;
                    default:
                        $activeStatus = 'Unknown';
                        $badgeClass = 'warning';
                }
                $statusHtml = '<span class="badge bg-' . $badgeClass . '">' . $activeStatus . '</span>';
            }
            // ✅ Package amount
            $packageAmount = '';
            if ($user->role === 'user') {
                $orders = Order::where('tx_user_id', $user->id)
                    ->whereIn('tx_type', ['topup', 'retopup'])
                    ->get();
                $totalamount = $orders->sum('order_amount');
                $packageAmount = company_info('currency', '$') . ' ' . number_format($totalamount, 2);
            }
            // ✅ Sponsor info
            $sponsorInfo = 'Admin';
            if ($user->sponsor) {
                $sponsorInfo = e($user->sponsor->name) . ' (' . e($user->sponsor->username) . ')';
            }
            $data[] = [
                $start + $index + 1,
                e($user->name),
                e($user->username),
                e($user->email),
                e($user->role),
                $sponsorInfo, // ✅ added sponsor column
                $statusHtml,
                $packageAmount,
                $user->created_at->format('Y-m-d H:i:s'),
                '
            <button class="btn btn-sm btn-primary me-1 btn-edit mt-1 mb-1"  data-id="' . $user->id . '">Edit</button>
    
            ' . $loginButton
            ];

            // <button class="btn btn-sm btn-danger me-1 btn-delete mt-1 mb-1" data-id="' . $user->id . '">Delete</button>
        }
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
    public function superadmincreate(Request $request)
    {
        // Basic validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ];
        // Dynamic custom field validation
        $customFields = CustomField::where('module_name', 'register')->orderBy('position')->get();
        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }
        $data = $request->validate($rules);
        // Get user_gen_prefix from Advance Info
        $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
        $randomNumber = mt_rand(10000000, 99999999);
        $username = $prefix . $randomNumber;
        $plainPassword = $data['password'];
        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $username,
            'password' => Hash::make($plainPassword),
            'role' => $data['role'],
        ]);
        // Handle custom field values
        $fieldIds = [];
        $fieldValues = [];
        if (isset($data['custom'])) {
            foreach ($data['custom'] as $fieldId => $value) {
                $field = $customFields->where('id', $fieldId)->first();
                if (!$field) continue;
                $storedValue = is_array($value) ? implode(',', $value) : $value;
                $countryCode = $request->input("country_code.$fieldId");
                if ($countryCode && (str_contains(strtolower($field->name), 'phone') || str_contains(strtolower($field->name), 'mobile') || str_contains(strtolower($field->name), 'whatsapp'))) {
                    $storedValue = $countryCode . $storedValue;
                }
                $fieldIds[] = $fieldId;
                $fieldValues[$fieldId] = $storedValue;
            }
            CustomFieldValue::create([
                'user_id' => $user->id,
                'custom_field_ids' => json_encode($fieldIds),
                'value' => json_encode($fieldValues),
                'module_name' => 'register',
            ]);
        }
        // Save custom field values in user
        $user->custom_fields = $fieldValues;
        $user->save();
        // Send registration email
        $registerMail = MailTemplate::where('slug', 'registion-template')->where('status', true)->first();
        if ($registerMail) {
            $tem = new UserRegisteredMail($username, $user->email, $plainPassword);
            //dd($tem);
            Mail::to($user->email)->send($tem);
            \Log::info("Mail sent to {$user->email} with username: {$username} and password: {$plainPassword}");
        }
        return redirect()->back()->with('success', 'User created successfully.');
    }
    public function superadminedit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function superadminupdate(Request $request, $id)
    {
        // Basic validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'status' => 'required|in:0,1,2',
            'password' => 'nullable|min:6|confirmed',
        ];
        // Dynamic custom field validation
        $customFields = CustomField::where('module_name', 'register')->orderBy('position')->get();
        foreach ($customFields as $field) {
            $rules["custom.{$field->id}"] = $field->required ? 'required' : 'nullable';
        }
        $data = $request->validate($rules);
        /* -------------------------------------------------
     | 2. Update the user
     * -------------------------------------------------*/
        $user           = User::findOrFail($id);
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->role     = $data['role'];
        $user->status   = $data['status'];
        // only hash & store if the caller actually sent a password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        // Handle custom fields only if role is user
        if ($user->role === 'user') {
            $fieldIds = [];
            $fieldValues = [];
            if (isset($data['custom'])) {
                foreach ($data['custom'] as $fieldId => $value) {
                    $field = $customFields->where('id', $fieldId)->first();
                    if (!$field) continue;
                    $storedValue = is_array($value) ? implode(',', $value) : $value;
                    $countryCode = $request->input("country_code.$fieldId");
                    if (
                        $countryCode &&
                        (str_contains(strtolower($field->name), 'phone') ||
                            str_contains(strtolower($field->name), 'mobile') ||
                            str_contains(strtolower($field->name), 'whatsapp'))
                    ) {
                        $storedValue = $countryCode . $storedValue;
                    }
                    $fieldIds[] = $fieldId;
                    $fieldValues[$fieldId] = $storedValue;
                }
                // Update or create custom field value row
                CustomFieldValue::updateOrCreate(
                    ['user_id' => $user->id, 'module_name' => 'register'],
                    [
                        'custom_field_ids' => json_encode($fieldIds),
                        'value' => json_encode($fieldValues),
                    ]
                );
                // Optionally store values on user (like in create)
                $user->custom_fields = $fieldValues;
                $user->save();
            }
        } else {
            // Delete custom field if it exists
            $customField = CustomFieldValue::where('user_id', $user->id)
                ->where('module_name', 'register form super admin')
                ->first();
            if ($customField) {
                $customField->delete();
            }
            // Reset the user's custom_fields column if applicable
            $user->custom_fields = null;
            $user->save();
        }
        return redirect()->back()->with('success', 'User updated successfully.');
    }
    public function superadmindestroy($id)
    {
        $user = User::findOrFail($id);
        // $user->delete();
        return response()->json(['success' => true]);
    }
    public function superadminprofile(Request $request)
    {
        $user = Auth::user();
        return view('superadmin.user_profile.index', compact('user'));
    }
    public function getUserName(Request $request)
    {
        $username = $request->username;
        $user = User::where('username', $username)->first();
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

// ─────────────────────────────────────────────────────────────────────────────
// Controller methods for ADMIN top‑up
// ─────────────────────────────────────────────────────────────────────────────
    /**
     * Show the top‑up form to an admin.
     *
     * » Lists every active package.
     * » No wallet selection / balance shown.
     */
    public function admintopupIndex(Request $request)
    {
        // ----- Packages ----------------------------------------------------------
        // Find the IDs of the custom‑field rows we care about once
        $customFieldMap = CustomField::where('module_name', 'pin_details')
            ->whereIn('name', ['pin rate', 'pin rate2'])
            ->pluck('id', 'name');
        $minRateId = $customFieldMap['pin rate']  ?? null;
        $maxRateId = $customFieldMap['pin rate2'] ?? null;
        // Build the package list (min / max are optional)
        $packages = PinDetail::where('status', 1)
            ->select('id', 'pin_type', 'custom_fields')
            ->get()
            ->map(function ($pin) use ($minRateId, $maxRateId) {
                $fields   = json_decode($pin->custom_fields, true);
                $minRate  = $minRateId ? ($fields[$minRateId] ?? null) : null;
                $maxRate  = $maxRateId ? ($fields[$maxRateId] ?? null) : null;
                return [
                    'id'   => $pin->id,
                    'name' => $pin->pin_type,
                    'min'  => $minRate,
                    'max'  => $maxRate,
                ];
            });
        // Top‑up type (pin / amount / pin amount) comes from settings
        $topupType = AdvanceInfo::where('label', 'topup_type')->value('value');
        return view('admin.users.topup', compact('packages', 'topupType'));
    }
    /**
     * Process an admin top‑up request.
     *
     * » No wallet lookup, no balance deduction.
     * » Everything else (order creation, first‑activation logic, etc.) unchanged.
     */
    public function adminstore(Request $request)
    {
        // ─── Basic validation ───────────────────────────────────────────────────
        $request->validate([
            'username'      => 'required|string',
            'packageAmount' => 'nullable|numeric|min:1',
            'package'       => 'nullable|numeric|min:1|exists:pin_details,id',
        ]);
        // ─── Receiver checks ────────────────────────────────────────────────────
        $receiver = User::where('username', $request->username)->first();
        if (!$receiver)          return back()->withErrors(['username' => 'Receiving user not found.']);
        if ($receiver->status == 2) return back()->withErrors(['username' => 'This user is blocked or inactive.']);
        // ─── Package / rate / ROI ───────────────────────────────────────────────
        $pinDetail = $request->package
            ? PinDetail::find($request->package)
            : PinDetail::where('status', 1)->orderBy('id')->first();
        if (!$pinDetail)         return back()->withErrors(['package' => 'No valid package found.']);
        // Map custom‑field IDs once
        $fieldMap = CustomField::where('module_name', 'pin_details')
            ->whereIn('name', ['roi', 'pin rate'])
            ->pluck('id', 'name');
        $fields     = json_decode($pinDetail->custom_fields, true);
        $roiValue   = $fieldMap['roi']      && isset($fields[$fieldMap['roi']])      ? $fields[$fieldMap['roi']]      : 0;
        $defaultAmt = $fieldMap['pin rate'] && isset($fields[$fieldMap['pin rate']]) ? $fields[$fieldMap['pin rate']] : null;
        $amount = $request->packageAmount ?? $defaultAmt;
        if (!$amount)            return back()->withErrors(['packageAmount' => 'Amount is required or pin rate not set.']);
        // ─── Prepare order info ────────────────────────────────────────────────
        $newActiveId  = (Order::max('active_id') ?? 0) + 1;
        $existingType = Order::where('tx_user_id', $receiver->id)
            ->where('package_name', $pinDetail->pin_type)
            ->exists();
        $txType  = $existingType ? 'retopup' : 'topup';
        $haveUsr = Order::where('tx_user_id', $receiver->id)->exists() ? 'haveuser' : 'notuser';
        // ─── First‑time activation actions (same as before) ────────────────────
        if ($haveUsr === 'notuser') {
            // Activate receiver
            UserWallet::firstOrNew(['u_code' => $receiver->id])->forceFill([
                'active_id' => $newActiveId,
            ])->save();
            $receiver->status = 1;
            $receiver->save();
            // Update sponsor counters up the chain
            $directUpline = User::where('username', $receiver->sponsor_id)->first();
            if ($directUpline) {
                $directWallet = UserWallet::firstOrNew(['u_code' => $directUpline->id]);
                $directWallet->c9  = ($directWallet->c9  ?? 0) + 1;      // direct count
                $directWallet->c10 = max(($directWallet->c10 ?? 0) - 1, 0);
                $directWallet->save();
                $currentSponsor = $receiver->sponsor_id;
                while ($currentSponsor) {
                    $upline = User::where('username', $currentSponsor)->first();
                    if (!$upline) break;
                    UserWallet::firstOrNew(['u_code' => $upline->id])
                        ->increment('c12');  // total team count
                    $currentSponsor = $upline->sponsor_id;
                }
            }
        }
        // ─── Create the order ──────────────────────────────────────────────────
        $prefix   = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
        $username = $prefix . mt_rand(10000000, 99999999);
        Order::create([
            'u_code'        => auth()->id(),   // the admin performing the action
            'active_id'     => $newActiveId,
            'tx_id'         => $username,
            'tx_user_id'    => $receiver->id,
            'tx_type'       => $txType,
            'order_mrp'     => $amount,
            'order_amount'  => $amount,
            'package_name'  => $pinDetail->pin_type,
            'roi'           => $roiValue,
            'times'         => 0,
            'roi_bv'        => 0,
            'expire_date'   => now()->addYear(),
            'status'        => 1,
            'payment_status' => 1,
            'payout_id'     => 0,
            'added_on'      => now(),
            'updated_on'    => now(),
        ]);
        return back()->with('success', 'Top‑up successful (admin).');
    }
}