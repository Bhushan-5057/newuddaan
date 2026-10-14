<?php

namespace App\Helpers;

use App\Models\Order;

class BvHelper
{
    public static function calculateTopupSplit($userId, $currentOrderBv)
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL TOPUP BV COMPLETED
        |--------------------------------------------------------------------------
        */

        $completedTopupBV = Order::where('u_code', $userId)
            ->where('tx_type', 'topup')
            ->where('status', 1)
            ->sum('order_bv');

        /*
        |--------------------------------------------------------------------------
        | TOPUP TARGET = 100 BV
        |--------------------------------------------------------------------------
        */

        $remainingTopupBV = max(0, 100 - $completedTopupBV);

        /*
        |--------------------------------------------------------------------------
        | SPLIT LOGIC
        |--------------------------------------------------------------------------
        */

        $topupBV = min($remainingTopupBV, $currentOrderBv);

        $retopupBV = max(0, $currentOrderBv - $topupBV);

        return [
            'topup_bv'   => $topupBV,
            'retopup_bv' => $retopupBV,
        ];
    }
}