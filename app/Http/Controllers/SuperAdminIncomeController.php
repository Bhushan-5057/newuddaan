<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;

use App\Models\WalletType;


use App\Models\AdvanceInfo;

use App\Models\CustomFieldValue;

use App\Models\UserWallet;

use App\Models\User;

use Illuminate\Support\Str;

use App\Models\MailTemplate;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegisteredMail;

use App\Models\Transaction;

use Carbon\Carbon;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class SuperAdminIncomeController extends Controller
{



    public function history($slug)
    {
        // ✅ Wallet Type Fetch
        $walletType = WalletType::where('slug', $slug)->firstOrFail();

        return view('superadmin.income.history', compact('walletType'));
    }

    public function historyData(Request $request, $slug)
    {
        $walletType = WalletType::where('slug', $slug)->firstOrFail();

        $query = DB::table('transaction')
            ->join('users', 'users.id', '=', 'transaction.u_code')
            ->where('transaction.tx_type', 'income')
            ->where('transaction.source', $walletType->source_key ?? $walletType->slug) // slug ya db mapping
            ->select(
                'transaction.*',
                'users.username',
                'users.name'
            );

        // ✅ Username Filter
        if ($request->filled('username')) {
            $query->where('users.username', 'like', '%' . $request->username . '%');
        }

        // ✅ Date Range Filter
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('transaction.date', [$start, $end]);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', fn($row) => number_format($row->amount, 2))
            ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
            ->editColumn('remark', fn($row) => $row->remark ?? '-')
            ->addColumn('username', fn($row) => $row->username ?? '-')
            ->addColumn('name', fn($row) => $row->name ?? '-')
            ->make(true);
    }



public function miningpool()

{

    return view('superadmin.income.mining_pool');

}




public function miningpoolHistoryData(Request $request)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', 'mining_pool')
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // ✅ Username Filter
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // ✅ Date Range Filter
    if ($request->filled('date_range')) {
        $dates = explode(' - ', $request->date_range);
        if (count($dates) === 2) {
            $start = Carbon::parse($dates[0])->startOfDay();
            $end = Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('transaction.date', [$start, $end]);
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('amount', fn($row) => number_format($row->amount, 2))
        ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
        ->editColumn('remark', fn($row) => $row->remark ?? '-')
        ->addColumn('username', fn($row) => $row->username ?? '-')
        ->addColumn('name', fn($row) => $row->name ?? '-')
        ->make(true);
}



public function directbonus()

{

    return view('superadmin.income.direct_bonus');

}
public function directbonusHistoryData(Request $request)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', 'direct_bonus')
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // ✅ Username Filter
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // ✅ Date Range Filter
    if ($request->filled('date_range')) {
        $dates = explode(' - ', $request->date_range);
        if (count($dates) === 2) {
            $start = Carbon::parse($dates[0])->startOfDay();
            $end = Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('transaction.date', [$start, $end]);
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('amount', fn($row) => number_format($row->amount, 2))
        ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
        ->editColumn('remark', fn($row) => $row->remark ?? '-')
        ->addColumn('username', fn($row) => $row->username ?? '-')
        ->addColumn('name', fn($row) => $row->name ?? '-')
        ->make(true);
}


public function matchingbonus()

{

    return view('superadmin.income.matching_bonus');

}

public function matchingbonusHistoryData(Request $request)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', 'matching_bonus')
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // ✅ Username Filter
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // ✅ Date Range Filter
    if ($request->filled('date_range')) {
        $dates = explode(' - ', $request->date_range);
        if (count($dates) === 2) {
            $start = Carbon::parse($dates[0])->startOfDay();
            $end = Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('transaction.date', [$start, $end]);
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('amount', fn($row) => number_format($row->amount, 2))
        ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
        ->editColumn('remark', fn($row) => $row->remark ?? '-')
        ->addColumn('username', fn($row) => $row->username ?? '-')
        ->addColumn('name', fn($row) => $row->name ?? '-')
        ->make(true);
}



public function rankBonus()

{

    return view('superadmin.income.rank_bonus');

}

public function rankBonusHistoryData(Request $request)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', 'rank_bonus')
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // ✅ Username Filter
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // ✅ Date Range Filter
    if ($request->filled('date_range')) {
        $dates = explode(' - ', $request->date_range);
        if (count($dates) === 2) {
            $start = Carbon::parse($dates[0])->startOfDay();
            $end = Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('transaction.date', [$start, $end]);
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('amount', fn($row) => number_format($row->amount, 2))
        ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
        ->editColumn('remark', fn($row) => $row->remark ?? '-')
        ->addColumn('username', fn($row) => $row->username ?? '-')
        ->addColumn('name', fn($row) => $row->name ?? '-')
        ->make(true);
}








public function miningbooster()

{

    return view('superadmin.income.mining_booster');

}


public function miningboosterHistoryData(Request $request)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', 'mining_booster')
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // ✅ Username Filter
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // ✅ Date Range Filter
    if ($request->filled('date_range')) {
        $dates = explode(' - ', $request->date_range);
        if (count($dates) === 2) {
            $start = Carbon::parse($dates[0])->startOfDay();
            $end = Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('transaction.date', [$start, $end]);
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('amount', fn($row) => number_format($row->amount, 2))
        ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('d-m-Y h:i A'))
        ->editColumn('remark', fn($row) => $row->remark ?? '-')
        ->addColumn('username', fn($row) => $row->username ?? '-')
        ->addColumn('name', fn($row) => $row->name ?? '-')
        ->make(true);
}









}