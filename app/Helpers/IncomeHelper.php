<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\AdvanceInfo;
use App\Models\WalletType;
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

    /*
    |--------------------------------------------------------------------------
    | DIRECT / REFERENCE INCOME (sponsor)
    |--------------------------------------------------------------------------
    | Same 30% rule as InvestController::processDirectSponsorIncome.
    | Used when a downline activates / tops up via product delivery Auto Topup
    | so sponsors receive Reference Income without requiring pin-panel topup.
    | Idempotent per product order_id when $orderId is provided.
    */

    public static function giveDirectSponsorIncome($receiver, $packageAmount, string $txType = 'topup', $orderId = null): void
    {
        try {
            if (!$receiver || (float) $packageAmount <= 0) {
                return;
            }

            if ($orderId) {
                $alreadyPaid = Transaction::where('order_id', $orderId)
                    ->where('tx_type', 'income')
                    ->where('source', 'direct')
                    ->exists();
                if ($alreadyPaid) {
                    return;
                }
            }

            $sponsor = User::where('username', $receiver->sponsor_id)
                ->where('status', 1)
                ->first();

            if (!$sponsor) {
                Log::info('Direct income skipped: sponsor missing/inactive', [
                    'receiver' => $receiver->username ?? $receiver->id,
                ]);
                return;
            }

            $calculatedIncome = ((float) $packageAmount * 30) / 100;
            $payable = self::applyDailyIncomeCapping($sponsor->id, $calculatedIncome, 'direct');

            if ($payable <= 0) {
                return;
            }

            $sponsorWallet = UserWallet::firstOrNew(['u_code' => $sponsor->id]);
            $openingBalance = (float) ($sponsorWallet->c1 ?? 0);
            $closingBalance = $openingBalance + $payable;

            $walletType = WalletType::where('wallet_column', 'c1')->first();
            $incomeNameData = WalletType::where('slug', 'direct')->first();
            $incomename = $incomeNameData->name ?? 'Direct Income';
            $incomeColumn = $incomeNameData->wallet_column ?? 'c16';

            $sponsorWallet->c1 = $closingBalance;
            $sponsorWallet->{$incomeColumn} = (float) ($sponsorWallet->{$incomeColumn} ?? 0) + $payable;
            $sponsorWallet->save();

            Transaction::create([
                'order_id'       => $orderId,
                'u_code'         => $sponsor->id,
                'tx_u_code'      => $receiver->id,
                'tx_type'        => 'income',
                'debit_credit'   => 'credit',
                'source'         => 'direct',
                'wallet_type'    => $walletType->name ?? 'Main Wallet',
                'amount'         => $payable,
                'open_wallet'    => $openingBalance,
                'closing_wallet' => $closingBalance,
                'date'           => now(),
                'status'         => 1,
                'remark'         => "30% {$incomename} from " . ucfirst($txType)
                    . " of user {$receiver->username} (Amount: {$packageAmount}) on " . now(),
                'added_on'       => now(),
            ]);

            Log::info('Direct income credited', [
                'sponsor' => $sponsor->username,
                'receiver' => $receiver->username,
                'amount' => $payable,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('Direct sponsor income failed', [
                'receiver_id' => $receiver->id ?? null,
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Daily income capping shared with pin-topup behaviour.
     * Returns payable amount, or 0 / negative when fully flushed.
     */
    private static function applyDailyIncomeCapping(int $userId, float $incomingAmount, string $source): float
    {
        $user = User::find($userId);
        $dailyLimit = (float) (AdvanceInfo::where('label', 'capping')->value('value') ?? 5000);

        $alreadyReceivedToday = (float) Transaction::where('u_code', $userId)
            ->where('tx_type', 'income')
            ->whereIn('source', ['roi', 'direct', 'binary', 'reward', 'rank'])
            ->whereDate('added_on', now()->toDateString())
            ->sum('amount');

        if ($alreadyReceivedToday >= $dailyLimit) {
            self::recordFlushAmount($userId, $incomingAmount, $source);
            self::markCappingHit($user);
            return 0;
        }

        $totalPotential = $alreadyReceivedToday + $incomingAmount;
        if ($totalPotential > $dailyLimit) {
            $payable = $dailyLimit - $alreadyReceivedToday;
            $flush = $incomingAmount - $payable;
            self::recordFlushAmount($userId, $flush, $source);
            self::markCappingHit($user);
            return max(0, $payable);
        }

        return $incomingAmount;
    }

    private static function markCappingHit($user): void
    {
        if ($user && (int) ($user->capping_hit_status ?? 0) === 0) {
            $user->capping_hit_status = 1;
            $user->capping_hit_date = now();
            $user->save();
        }
    }

    private static function recordFlushAmount(int $userId, float $amount, string $source): void
    {
        if ($amount <= 0) {
            return;
        }

        Transaction::create([
            'u_code' => $userId,
            'tx_type' => 'flush_out',
            'debit_credit' => 'debit',
            'source' => $source,
            'amount' => $amount,
            'date' => now(),
            'status' => 1,
            'remark' => "Amount Flush Out due to Daily Capping Limit of user ID {$userId} for source {$source} Amount: {$amount}",
            'added_on' => now(),
        ]);
    }
}