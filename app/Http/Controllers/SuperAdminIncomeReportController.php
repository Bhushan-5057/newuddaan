<?php   

namespace App\Http\Controllers;


use App\Exports\UserIncomeReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\WalletType;

class SuperAdminIncomeReportController extends Controller
{
  public function index(Request $request)
    {
        

        // ── 1. Date range -------------------------------------------------------
        $range      = $request->range;
        $fromInput  = $request->from;
        $toInput    = $request->to;

        if (!$range && $fromInput && $toInput) {
            $range = 'custom';
        }
        [$fromDate, $toDate] = $this->getDateRange($range, $fromInput, $toInput);

        // ── 2. Get **every** active income‑type record -------------------------
        $incomeTypes = WalletType::where('wallet_type', 'income')
            ->where('universal', 1)
            ->where('status', 1)
            ->get();

        // ── 3. Build amounts dynamically ---------------------------------------
        $incomeData = [];
        $grandTotal = 0;

        foreach ($incomeTypes as $type) {
            $amount = DB::table('transaction')
                ->where([
                    ['tx_type', '=', 'income'],
                    ['source',   '=', $type->slug],
                ])
                ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('date', [$fromDate->startOfDay(), $toDate->endOfDay()]);
                })
                ->sum('amount');

          $incomeData[] = [
    'label'  => $type->sidebar_name ?? $type->source,
    'amount' => $amount,
    'name'   => $type->name,        // <-- use $type, not $incomeTypes
    'user_route'   => $type->user_route,        // <-- use $type, not $incomeTypes
 
];


            $grandTotal += $amount;
        }

        return view('superadmin.income-report.index', compact('incomeData', 'grandTotal'));
    }

    public function downloadExcel(Request $request)
    {
        $userId = auth()->id();

        $range = $request->range;
        $from = $request->from;
        $to = $request->to;

        // Auto fallback to 'custom' if from and to are filled but range is missing
        if (!$range && $from && $to) {
            $range = 'custom';
        }

        [$fromDate, $toDate] = $this->getDateRange($range, $from, $to);

        // Query
        $query = DB::table('transaction')
            ->where('u_code', $userId)
            ->where('tx_type', 'income');

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate->startOfDay(), $toDate->endOfDay()]);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        // Headers
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=income-report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'Source', 'Amount'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $row) {
                fputcsv($file, [
                    '="' . Carbon::parse($row->date)->format('Y-m-d H:i:s') . '"', // Excel-friendly date format
                    $row->source,
                    number_format($row->amount, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper method to get start/end dates based on filter
     */
    protected function getDateRange($range, $from, $to)
    {
        switch ($range) {
            case 'today':
                return [Carbon::today(), Carbon::today()];
            case 'yesterday':
                return [Carbon::yesterday(), Carbon::yesterday()];
            case 'last7':
                return [Carbon::now()->subDays(6), Carbon::now()];
            case 'custom':
                return [
                    $from ? Carbon::parse($from) : null,
                    $to ? Carbon::parse($to) : null
                ];
            default:
                return [null, null]; // No filtering
        }
    }

}
