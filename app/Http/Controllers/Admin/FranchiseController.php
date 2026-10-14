<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseUser;
use App\Models\TransactionFranchise;
use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class FranchiseController extends Controller
{
    // Franchise list
    public function index()
    {
        $total = FranchiseUser::count();
        $active = FranchiseUser::where('active_status', 1)->count();
        $inactive = FranchiseUser::where('active_status', 0)->count();

        return view('admin.franchise.index', compact(
            'total',
            'active',
            'inactive'
        ));
    }

    public function getData(Request $request)
    {
        $draw   = intval($request->draw);
        $start  = intval($request->start);
        $length = intval($request->length);

        $baseQuery = FranchiseUser::query();

        // TOTAL COUNT (no filter)
        $recordsTotal = $baseQuery->count();

        /* ---------------- FILTERS ---------------- */

        if ($request->filled('status')) {
            $baseQuery->where('active_status', $request->status);
        }

        if ($request->filled('from_date')) {
            $baseQuery->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('created_at', '<=', $request->to_date);
        }

        if ($search = $request->input('search.value')) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // FILTERED COUNT
        $recordsFiltered = $baseQuery->count();

        /* ---------------- DATA ---------------- */

        $franchises = $baseQuery
            ->orderBy('id', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $data = [];

        foreach ($franchises as $index => $f) {

            $data[] = [
                '<input type="checkbox" value="' . $f->id . '">',

                $start + $index + 1,

                '
                    <form method="POST"
                        action="' . route('admin.franchise.impersonate', $f->id) . '"
                        target="_blank"
                        style="display:inline">
                        ' . csrf_field() . '
                        <button class="btn btn-sm btn-warning me-1">Login</button>
                    </form>

                    <a href="' . route('admin.franchise.dashboard', $f->id) . '"
                    class="btn btn-sm btn-success me-1">View</a>



                            <button type="button"
                        class="btn btn-sm btn-primary btn-edit"
                        data-id="' . $f->id . '">
                    Edit
                </button>

                    ',

                e($f->name),
                e($f->username),
                e($f->email),

                $f->active_status
                    ? '<span class="badge bg-success">ACTIVE</span>'
                    : '<span class="badge bg-secondary">INACTIVE</span>',

                $f->created_at->format('Y-m-d'),
            ];
        }

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }


    public function impersonateLogin($id)
    {
        $franchise = FranchiseUser::findOrFail($id);

        session([
            'impersonator_id' => auth()->id(),
            'impersonator_guard' => 'web',
            'is_impersonating' => true,
        ]);

        auth()->guard('franchise')->login($franchise);

        return redirect()
            ->route('franchise.dashboard')
            ->with('success', 'Logged in as Franchise');
    }


    public function edit($id)
    {
        return FranchiseUser::findOrFail($id);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:200',
            'email'  => ['required', 'email', Rule::unique('franchise_users')->ignore($id)],
            'mobile' => 'required|digits_between:10,15',

            'country'   => 'required|string|max:100',
            'state'     => 'required|string|max:100',
            'city'      => 'required|string|max:100',
            'pin_code'  => 'required|string|max:10',
            'address'   => 'required|string',

            'franchise_name' => 'required|string|max:100',
            'active_status'  => 'required|in:0,1',
            'password'       => 'nullable|min:6|confirmed',
        ]);

        $franchise = FranchiseUser::findOrFail($id);

        $franchise->fill($request->except(['password', 'password_confirmation']));

        if ($request->filled('password')) {
            $franchise->password = Hash::make($request->password);
        }

        $franchise->save();

        return response()->json(['success' => true]);
    }




    // Franchise create form


    public function create()
    {
        return view('admin.franchise.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // BASIC
            'name'              => 'required|string|max:200',
            'username'          => 'required|string|max:50|unique:franchise_users,username',
            'email'             => 'required|email|max:100|unique:franchise_users,email',
            'mobile'            => 'required|digits_between:10,15',

            // NOMINEE
            'nominee_relation'  => 'nullable|string|max:50',
            'nominee_name'      => 'nullable|string|max:100',

            // FRANCHISE
            'franchise_name'    => 'required|string|max:100',
            'pan_no'            => 'nullable|string|max:20',
            'franchise_gst'     => 'nullable|string|max:50',

            // ADDRESS
            'country'           => 'required|string|max:100',
            'state'             => 'required|string|max:100',
            'city'              => 'required|string|max:100',
            'pin_code'          => 'required|string|max:10',
            'address'           => 'required|string',

            // PASSWORD
            'password'          => 'required|min:6|confirmed',
        ]);

        FranchiseUser::create([
            'u_sponsor'        => auth()->id(),
            'username'         => $request->username,
            'name'             => $request->name,
            'nominee_relation' => $request->nominee_relation,
            'nominee_name'     => $request->nominee_name,
            'franchise_name'   => $request->franchise_name,
            'email'            => $request->email,
            'mobile'           => $request->mobile,
            'pan_no'           => $request->pan_no,
            'franchise_gst'    => $request->franchise_gst,
            'address'          => $request->address,
            'country'          => $request->country,
            'city'             => $request->city,
            'state'            => $request->state,
            'pin_code'         => $request->pin_code,
            'password'         => Hash::make($request->password),
            'active_status'    => 1,
        ]);

        return redirect()
            ->route('admin.franchise.index')
            ->with('success', 'Franchise Registered Successfully');
    }

    // Franchise dashboard (basic)
    public function dashboard($id)
    {
        $franchise = FranchiseUser::findOrFail($id);
        return view('admin.franchise.dashboard', compact('franchise'));
    }



public function fundRequests(Request $request)
{
    $requests = TransactionFranchise::where('tx_type', 'fund_request')
                ->when($request->status !== null, function($q) use($request){
                    return $q->where('status', $request->status);
                })
                ->latest()->paginate(15);
    return view('admin.franchise.fund_requests', compact('requests'));
}

public function viewFundRequest($id)
{
    $txn = TransactionFranchise::findOrFail($id);
    return view('admin.franchise.fund_view', compact('txn'));
}

public function updateFundStatus(Request $request)
{
    $request->validate(['id' => 'required', 'status' => 'required|in:1,2']);
    $txn = TransactionFranchise::findOrFail($request->id);
    if($txn->status != 0) return back()->with('error', 'Processed already.');

    $franchise = FranchiseUser::where('username', $txn->u_code)->first();

    if ($request->status == 1) {
        DB::transaction(function () use ($txn, $franchise) {
            $newBal = $franchise->balance + $txn->amount;
            $txn->update(['status' => 1, 'open_wallet' => $franchise->balance, 'closing_wallet' => $newBal]);
            $franchise->update(['balance' => $newBal]);
        });
        return redirect()->route('admin.franchise.fund.requests')->with('success', 'Fund Approved!');
    }
    
    $txn->update(['status' => 2]);
    return redirect()->route('admin.franchise.fund.requests')->with('success', 'Fund Rejected.');
}
}
