<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Transaction;
use App\Models\AdvanceInfo;
use App\Models\WalletType;
use App\Models\KycDetail;
use App\Models\Order;
use App\Models\FranchiseOrder;
use App\Models\TransactionFranchise;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Auth;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    


    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
         Paginator::useBootstrapFive();
        
            View::composer('*', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            $kyc = $user->kycDetail;

            // Only the sections a member can actually submit count towards this
            // - see KycDetail::ACTIVE_SECTIONS. The disabled 'personal' section
            // is stuck on 'pending' for everyone, so including it left the menu
            // blinking even for fully approved members.
            $kycState = $kyc ? $kyc->overallState() : 'missing';

            // 'submitted' is still awaiting review, which the menu shows the
            // same way as pending.
            if ($kycState === 'submitted') {
                $kycState = 'pending';
            }

            $view->with('kycState', $kycState);
        }
    });

        // Resolved once per request. This composer fires for every view -
        // layouts, partials and all - so a fresh query here meant several
        // identical SELECTs on each page.
        View::composer('*', function ($view) {
            static $categories = null;

            if ($categories === null) {
                $categories = Category::where('status', 1)->get();
            }

            $view->with('categories', $categories);
        });
        
        /*
        |----------------------------------------------------------------------
        | Superadmin Layout Data
        |----------------------------------------------------------------------
        */
        View::composer('superadmin.layouts.app', function ($view) {
            $pendingWithdrawalCount = Transaction::where('status', 0)
                ->where('tx_type', 'withdrawal')
                ->where('is_seen', 0)
                ->count();

            $pendingFundRequestCount = Transaction::where('status', 0)
                ->where('tx_type', 'user_deposit')
                ->where('is_seen', 0)
                ->count();

            $view->with([
                'pendingWithdrawalCount' => $pendingWithdrawalCount,
                'pendingFundRequestCount' => $pendingFundRequestCount,
            ]);
        });

        /*
        |----------------------------------------------------------------------
        | Admin Layout Data
        |----------------------------------------------------------------------
        */
        View::composer('admin.layouts.app', function ($view) {
            // Three unseen-pending counters that differ only by tx_type, so one
            // grouped query answers all of them. (The withdrawal count was also
            // being run twice, identically.)
            $unseenPending = Transaction::select('tx_type')
                ->selectRaw('COUNT(*) AS total')
                ->where('status', 0)
                ->where('is_seen', 0)
                ->whereIn('tx_type', ['withdrawal', 'Principal_Withdrawal', 'user_deposit'])
                ->groupBy('tx_type')
                ->pluck('total', 'tx_type');

            $pendingwithdrwalsrequest          = (int) ($unseenPending['withdrawal'] ?? 0);
            $pendingPrincipalwithdrwalsrequest = (int) ($unseenPending['Principal_Withdrawal'] ?? 0);
            $pendingfundrequest                = (int) ($unseenPending['user_deposit'] ?? 0);

            $pendingProductOrdersCount = Order::where('tx_type', 'product')
                                ->where('status', 0)
                                ->count();
         

            $pendingFranchiseOrders = FranchiseOrder::where('status', 0)->count();

            $pendingFranchiseFundRequestCount = TransactionFranchise::where('status', 0)
                ->where('tx_type', 'fund_request')
                ->count();

            // Pending KYC requests. Counts only the sections a member can
            // actually submit - see KycDetail::ACTIVE_SECTIONS.
            $pendingKycCount = KycDetail::whereAnySection('submitted')->count();

            $view->with([
                'pendingwithdrwalsrequest' => $pendingwithdrwalsrequest,
                'pendingPrincipalwithdrwalsrequest' => $pendingPrincipalwithdrwalsrequest,
                'pendingfundrequest'       => $pendingfundrequest,
                'pendingKycCount'          => $pendingKycCount,
                'pendingFranchiseOrders'   => $pendingFranchiseOrders,
                'pendingFranchiseFundRequestCount'   => $pendingFranchiseFundRequestCount,
                'pendingProductOrdersCount'    => $pendingProductOrdersCount,
            ]);
        });

        /*
        |----------------------------------------------------------------------
        | Global Data (Available in ALL Views)
        |----------------------------------------------------------------------
        */
        $RegistrationType = AdvanceInfo::where('label', 'reg_type')->value('value');

        $incometype = WalletType::where('wallet_type', 'income')
            ->where($RegistrationType, '1')
            ->where('status', true)
            ->get();

        // 🔥 Share globally
        View::share([
            'RegistrationType' => $RegistrationType,
            'incometype'       => $incometype,
        ]);
    }
}
