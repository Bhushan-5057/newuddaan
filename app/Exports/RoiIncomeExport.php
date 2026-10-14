<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoiIncomeExport implements FromView
{
    protected $username;
    protected $date_range;

    public function __construct($username = null, $date_range = null)
    {
        $this->username = $username;
        $this->date_range = $date_range;
    }

    public function view(): View
    {
        $query = \DB::table('transaction')
            ->join('users', 'users.id', '=', 'transaction.u_code')
            ->where('transaction.tx_type', 'income')
            ->where('transaction.source', 'staking_reward');

        if ($this->username) {
            $query->where('users.username', 'like', '%' . $this->username . '%');
        }

        if ($this->date_range) {
            [$start, $end] = explode(' - ', $this->date_range);
            $query->whereBetween('transaction.date', [$start . ' 00:00:00', $end . ' 23:59:59']);
        }

        $data = $query
            ->select('users.username', 'users.name', 'transaction.amount', 'transaction.remark', 'transaction.date')
            ->orderBy('transaction.date', 'desc')
            ->get();

        return view('admin.exports.roi_income', ['data' => $data]);
    }
}
