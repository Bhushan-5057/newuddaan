<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserIncomeReportExport implements FromView
{
    protected $from, $to, $range;

    public function __construct($from = null, $to = null, $range = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->range = $range;
    }

    public function view(): View
    {
        $userId = auth()->user()->id;

        // Handle date ranges
        $from = $to = null;
        switch ($this->range) {
            case 'today':
                $from = Carbon::today();
                $to = Carbon::today();
                break;
            case 'yesterday':
                $from = Carbon::yesterday();
                $to = Carbon::yesterday();
                break;
            case 'last7':
                $from = Carbon::now()->subDays(6);
                $to = Carbon::now();
                break;
            case 'custom':
                $from = $this->from ? Carbon::parse($this->from) : null;
                $to = $this->to ? Carbon::parse($this->to) : null;
                break;
        }

        $baseQuery = function ($source) use ($userId, $from, $to) {
            $query = DB::table('transaction')
                ->where('u_code', $userId)
                ->where('tx_type', 'income')
                ->where('source', $source);

            if ($from && $to) {
                $query->whereBetween('date', [$from->startOfDay(), $to->endOfDay()]);
            }

            return $query;
        };

        $roiIncome = $baseQuery('roi')->sum('amount');
        $directIncome = $baseQuery('direct_income')->sum('amount');
        $levelIncome = $baseQuery('roi_level_income')->sum('amount');
        $rewardIncome = $baseQuery('rank_reward')->sum('amount');
        $monthlySalary = $baseQuery('monthly_salary')->sum('amount');
        $total = $roiIncome + $directIncome + $levelIncome + $rewardIncome + $monthlySalary;

        return view('user.income-report.export', compact(
            'roiIncome',
            'directIncome',
            'levelIncome',
            'rewardIncome',
            'monthlySalary',
            'total'
        ));
    }
}

