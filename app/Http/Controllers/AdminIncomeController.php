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


class AdminIncomeController extends Controller
{



    /** Show Income History Page (Dynamic by Slug) */
    
public function incomeHistory($slug)
{
    $baseQuery = DB::table('transaction')
        ->where('tx_type', 'income')
        ->where('source', $slug);

    $totalAmount = $baseQuery->sum('amount');
    $totalCount  = $baseQuery->count();

    return view('admin.income.history', compact(
        'slug',
        'totalAmount',
        'totalCount'
    ));
}

public function incomeHistoryData(Request $request, $slug)
{
    $query = DB::table('transaction')
        ->join('users', 'users.id', '=', 'transaction.u_code')
        ->where('transaction.tx_type', 'income')
        ->where('transaction.source', $slug)
        ->select(
            'transaction.*',
            'users.username',
            'users.name'
        );

    // USERNAME FILTER
    if ($request->filled('username')) {
        $query->where('users.username', 'like', '%' . $request->username . '%');
    }

    // FROM / TO DATE FILTER
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('transaction.date', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // FILTERED TOTALS
    $filteredAmount = (clone $query)->sum('amount');
    $filteredCount  = (clone $query)->count();

    return DataTables::of($query)
        ->editColumn('amount', fn ($r) => number_format($r->amount, 2))
        ->editColumn('date', fn ($r) => Carbon::parse($r->date)->format('d-m-Y h:i A'))
        ->addColumn('username', fn ($r) => $r->username ?? '-')
        ->addColumn('name', fn ($r) => $r->name ?? '-')
        ->with([
            'filteredAmount' => number_format($filteredAmount, 2),
            'filteredCount'  => $filteredCount,
        ])
        ->make(true);
}


    public function downloadIncomeExcel(Request $request, $slug)
    {
        $username  = $request->username;
        $dateRange = $request->date_range;

        $fromDate = $toDate = null;
        if (!empty($dateRange)) {
            [$fromDate, $toDate] = explode(' - ', $dateRange);
            $fromDate = Carbon::parse($fromDate)->startOfDay();
            $toDate   = Carbon::parse($toDate)->endOfDay();
        }

        $query = DB::table('transaction')
            ->join('users', 'users.id', '=', 'transaction.u_code')
            ->where('transaction.tx_type', 'income')
            ->where('transaction.source', $slug)
            ->select('transaction.*', 'users.username', 'users.name');

        if (!empty($username)) {
            $query->where('users.username', $username);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('transaction.date', [$fromDate, $toDate]);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$slug}-income-report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = ['Sr No', 'Username', 'Name', 'Amount', 'Remark', 'Date'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $sr = 1;
            foreach ($transactions as $row) {
                fputcsv($file, [
                    $sr++,
                    $row->username,
                    $row->name,
                    number_format($row->amount, 2),
                    $row->remark ?? '-',
                    Carbon::parse($row->date)->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }










}
