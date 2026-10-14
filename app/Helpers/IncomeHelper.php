<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\AdvanceInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\BinaryPlacementTrait;

class IncomeHelper
{
    use BinaryPlacementTrait;

    /*
    |--------------------------------------------------------------------------
    | FREE IDS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Generate a unique username with prefix from advance_info
     * Format: {prefix}{sequential_number}
     * Example: NU20001, NU20002, NU20003 etc.
     */
    public static function generateUsername() // <-- અહીં public static કર્યું
    {
        // Get prefix from advance_info table
        $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'NU';
        
        // Find the last username with this prefix and extract the number
        $lastUser = User::where('username', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastUser) {
            // Extract the number from the last username
            $lastNumber = intval(substr($lastUser->username, strlen($prefix)));
            $newNumber = $lastNumber + 1;
        } else {
            // Start from 20001 if no user exists with this prefix
            $newNumber = 20001;
        }
        
        $username = $prefix . $newNumber;
        
        // Safety check: Ensure username doesn't exist
        while (User::where('username', $username)->exists()) {
            $newNumber++;
            $username = $prefix . $newNumber;
        }
        
        return $username;
    }


    public static function createFreeIds($user)
    {
        try {

            $alreadyCreated = User::where('u_sponsor', $user->id)
                ->where('is_free_id', 1)
                ->exists();

            if ($alreadyCreated) return;

            $helper = new self();

            $positions = ['left', 'right'];

            $userGenDigit = (int) (
                AdvanceInfo::where('label', 'user_gen_digit')
                ->value('value') ?? 6
            );

            $prefix = AdvanceInfo::where('label', 'user_gen_prefix')
                ->value('value') ?? 'USER';

            foreach ($positions as $position) {

                // અહીં $this-> ની જગ્યાએ self:: નો ઉપયોગ કર્યો છે
                $username = self::generateUsername();

                $plainPassword = '123456';

                $placement = $helper->getBinaryPlacement(
                    $user,
                    $position
                );

                $freeUser = User::create([

                    'name' => $user->name . ' (' . ucfirst($position) . ')',

                    'email' => $user->email . '_' . $position . '_' . time(),

                    'username' => $username,
                    'unique_name' => $username,

                    'password' => Hash::make($plainPassword),

                    'sponsor_id' => $user->username,

                    'u_sponsor' => $user->id,

                    'parent_id' => $placement['parent']->id,

                    'position' => $placement['position'],

                    'left_id' => null,

                    'right_id' => null,

                    'left_count' => 0,

                    'right_count' => 0,

                    'join_date' => now(),

                    'status' => 1,

                    'is_free_id' => 1,
                ]);

                if ($placement['position'] == 1)
                    $placement['parent']->update([
                        'left_id' => $freeUser->id
                    ]);
                else
                    $placement['parent']->update([
                        'right_id' => $freeUser->id
                    ]);

                UserWallet::create([
                    'u_code' => $freeUser->id
                ]);

                Log::info(
                    "Free ID Created - Username: {$username}, Password: {$plainPassword}"
                );
            }

        } catch (\Exception $e) {

            Log::error('Failed to create free IDs', [

                'user_id' => $user->id,

                'error' => $e->getMessage()
            ]);
            
            // Re-throw exception so caller can log/catch it if needed
            throw $e;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CASHBACK BONUS
    |--------------------------------------------------------------------------
    */

    public static function giveCashbackBonus($order, $productBV)
    {
        try {

            $alreadyReceived = Transaction::where('u_code', $order->u_code)
                ->where('tx_type', 'cashback_bonus')
                ->exists();

            if ($alreadyReceived) return;

            $cashbackWallet = UserWallet::firstOrNew([
                'u_code' => $order->u_code
            ]);

            $opening = $cashbackWallet->c28 ?? 0;

            $cashbackWallet->c28 = $opening + 1000;
             $cashbackWallet->c1=($cashbackWallet->c1 ?? 0) + 1000;

            $cashbackWallet->save();

            Transaction::create([

                'order_id'       => $order->id,

                'u_code'         => $order->u_code,

                'tx_u_code'      => $order->u_code,

                'tx_type'        => 'income',

                'source'         => 'cashback',

                'debit_credit'   => 'credit',

                'wallet_type'    => 'cashback_wallet',

                'amount'         => 1000,

                'payment_type'   => 'cashback',

                'status'         => 1,

                'open_wallet'    => $opening,

                'closing_wallet' => $opening + 1000,

                'remark'         => "{$productBV} BV Cashback Bonus",

                'added_on'       => now(),
            ]);

        } catch (\Exception $e) {

            Log::error('Cashback Bonus Failed', [

                'order_id' => $order->id,

                'error' => $e->getMessage()
            ]);
        }
    }
}