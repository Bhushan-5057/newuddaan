<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyPaymentMethod;
use App\Models\UserPaymentMethod;
  use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CompanyPaymentMethodController extends Controller
{


public function create()
{
    $methods = CompanyPaymentMethod::where('status', 1)->get();
    $userMethods = UserPaymentMethod::where('u_code', Auth::id())->get();
    return view('user.account_settings.index', compact('methods', 'userMethods'));
}

public function store(Request $request)
{
    $request->validate([
        'method' => 'required|string',
        'additional_fields' => 'required|array',
    ]);

    $u_code = auth()->id();
    $accountType = $request->method;

    // 🔁 Collect all dynamic fields and include the account type
    $additionalFields = $request->additional_fields;
    $additionalFields['account_type'] = $accountType;

    // Store everything in an array (supports future multiple accounts per type)
    $formattedData = [ $additionalFields ];

    // 🔍 Check if same account_type already exists for this user
    $existing = UserPaymentMethod::where('u_code', $u_code)
        ->get()
        ->first(function ($entry) use ($accountType) {
            $data = json_decode($entry->accounts, true);
            return isset($data[0]['account_type']) && $data[0]['account_type'] === $accountType;
        });

    if ($existing) {
        // ✅ Update existing record
        $existing->accounts = json_encode($formattedData);
        $existing->save();
    } else {
        // 🛑 Limit check (optional)
        if (UserPaymentMethod::where('u_code', $u_code)->count() >= 10) {
            return back()->with('error', 'You can only save up to 10 methods.');
        }

        // ✅ Create new record
        UserPaymentMethod::create([
            'u_code' => $u_code,
            'accounts' => json_encode($formattedData),
            'status' => 1,
            'default_account' => 0
        ]);
    }

    return back()->with('success', 'Payment method saved successfully.');
}





public function delete($id)
{
    $method = UserPaymentMethod::where('id', $id)->where('u_code', Auth::id())->first();
    if ($method) {
        $method->delete();
        return back()->with('success', 'Deleted successfully.');
    }
    return back()->with('error', 'Not found.');
}

public function setDefault(Request $request)
{
    $id = $request->id;

    UserPaymentMethod::where('u_code', Auth::id())->update(['default_account' => 0]);

    UserPaymentMethod::where('u_code', Auth::id())->where('id', $id)->update(['default_account' => 1]);

    return response()->json(['success' => true]);
}


}
