<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserWallet;
use App\Models\WalletType;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Order;
use App\Models\PinDetail;
use App\Models\NoticeBoard;
use App\Models\AdvanceInfo;
use App\Models\CustomField;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\FranchiseUser;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\MatchingBonusHistory;
use App\Models\AdminPowerBusiness; // નવું મોડલ એડ કર્યું
use App\Helpers\UserHelper;



class DashboardController extends Controller
{




    public function franchisedashboard()
    {
        $franchise = Auth::guard('franchise')->user();

        // BASIC INFO
        $franchiseId = $franchise->id;
        $status = $franchise->active_status ? 'Active' : 'Inactive';

        // ORDERS (future mapping ready)
        $totalOrders = Order::where('tx_user_id', $franchiseId)->count();
        $todayOrders = Order::where('tx_user_id', $franchiseId)
            ->whereDate('added_on', Carbon::today())
            ->count();

        // SALES
        $totalSales = Order::where('tx_user_id', $franchiseId)
            ->where('status', 1)
            ->sum('order_amount');

        $todaySales = Order::where('tx_user_id', $franchiseId)
            ->where('status', 1)
            ->whereDate('added_on', Carbon::today())
            ->sum('order_amount');

        // PRODUCTS / STOCK (optional – future)
        $totalProducts = Product::count(); // or franchise mapped later

        return view('franchise.dashboard.index', compact(
            'franchise',
            'status',
            'totalOrders',
            'todayOrders',
            'totalSales',
            'todaySales',
            'totalProducts'
        ));
    }

    public function userdashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Registration type
        $RegistrationType = AdvanceInfo::where('label', 'reg_type')->value('value');

        // Wallets
        $teamwallets = WalletType::where('wallet_type', 'team')->where('status', true)->where($RegistrationType, true)->get();
        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();
        // Dashboard cards: Active Bonus → Matching → Reference,
        // then Total Income (blade) → My Wallet (wallets loop).
        // Always include Reference Income (slug=direct) when status=1 even if the
        // reg_type column flag is off — live DB had generation=0 and dropped the card.
        $incomeCardOrder = ['reward', 'binary', 'direct'];
        $incometype = WalletType::where('wallet_type', 'income')
            ->where('status', true)
            ->whereIn('slug', $incomeCardOrder)
            ->where(function ($q) use ($RegistrationType) {
                $q->where($RegistrationType, '1')
                    ->orWhere('slug', 'direct');
            })
            ->get()
            ->unique('slug')
            ->sortBy(function ($income) use ($incomeCardOrder) {
                $pos = array_search($income->slug, $incomeCardOrder, true);

                return $pos === false ? 999 : $pos;
            })
            ->values();
        $teamtype = WalletType::where('wallet_type', 'team')
            ->where($RegistrationType, '1')
            ->where('status', true)
            ->get();


            /*
            --------------------------------
            RUNNING MONTH PACKAGE CHECK
            --------------------------------
            */
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth   = Carbon::now()->endOfMonth();
            
            $status_invesment = 'No Active Order';
            
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            
            $lastOrder = Order::where('tx_user_id', $userId)
                ->whereIn('tx_type', ['topup','retopup'])
                ->where('status', 1)
                ->whereBetween('added_on', [$startOfMonth, $endOfMonth])
                ->orderByDesc('id')
                ->first();
            
            if ($lastOrder) {
            
                $orderMonth = Carbon::parse($lastOrder->added_on)->month;
                $orderYear = Carbon::parse($lastOrder->added_on)->year;
            
                if ($orderMonth == $currentMonth && $orderYear == $currentYear) {
                    $status_invesment = 'Active';
                } else {
                    $status_invesment = 'Inactive';
                }
            }
            
            
        $userwallet = UserWallet::firstOrNew(['u_code' => $userId]);

        // Rank achievement
        $rankTransaction = Transaction::where('tx_type', 'income')
            ->where('u_code', $userId)
            ->where('source', 'rank_reward')
            ->latest('id')->first();
        $rank = null;
        if ($rankTransaction && preg_match('/Rank\s+([A-Z\s]+)\s+reward/i', $rankTransaction->remark, $matches)) {
            $rank = trim($matches[1]);
        }

        // Withdrawals summary
        $totalWithdrawal = Transaction::where('tx_type', 'withdrawal')
            ->where('u_code', $userId)
            ->where('status', '!=', 2)
            ->sum('amount');
        $totalincome = Transaction::where('tx_type', 'income')
            ->where('u_code', $userId)
            ->sum('amount');

        $pendingWithdrawal = Transaction::where('tx_type', 'withdrawal')
            ->where('u_code', $userId)
            ->where('status', 0)
            ->sum('amount');
        $paidWithdrawal = Transaction::where('tx_type', 'withdrawal')
            ->where('u_code', $userId)
            ->where('status', 1)
            ->sum('amount');
        $rejectedWithdrawal = Transaction::where('tx_type', 'withdrawal')
            ->where('u_code', $userId)
            ->where('status', 2)
            ->sum('amount');

        // All topup/retopup orders
        $orders = Order::where('tx_user_id', $userId)
            ->whereIn('tx_type', ['topup', 'retopup'])->where('status', 1)
            ->get();
            
            
        $totalamount = Order::where('u_code', $userId)->where('tx_type', 'product')->where('status', 2)->sum('order_amount');

        // Assuming NoticeBoard model exists
        $popupNotices = NoticeBoard::where('type', 'popup')
            ->where('status', 1)
            ->orderBy('added_on', 'desc')
            ->get();



        $marqueeNotices = NoticeBoard::where('type', 'marquee')
            ->where('status', 1)
            ->orderBy('added_on', 'desc')
            ->get();


        // Income Breakdown
        $baseQuery = function ($source) use ($userId) {
            return DB::table('transaction')
                ->where('u_code', $userId)
                ->where('tx_type', 'income')
                ->where('source', $source);
        };

        $totalincomegss = Transaction::where('u_code', $userId)
            ->where('tx_type', 'income')
            ->whereIn('source', [
                'roi', 'level', 'reward', 'royalty', 'stake_level', 'direct', 'binary',
                'binary_topup', 'binary_retopup',
            ])
            ->sum('amount');

        $packageAmount = Order::where('tx_user_id', $userId)
            ->where('status', 1)->whereIn('tx_type', ['topup', 'retopup'])
            ->sum('order_amount');

        // BV booked on the user's own packages. Same definition the genealogy
        // tree uses for its 100-BV threshold, so the dashboard card and the
        // tree node agree about a user's standing.
        $packageBv = (float) Order::where('tx_user_id', $userId)
            ->where('status', 1)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->sum('order_bv');

   

        // Check if user has direct downline users
        $hasDirectDownlines = User::where('sponsor_id', $user->username)->where('status', 1)->exists();

 



        // Directs count
        $totalDirects = User::where('sponsor_id', $userId)->count();
        $activeDirects = User::where('sponsor_id', $userId)->where('status', 1)->count();
        $inactiveDirects = User::where('sponsor_id', $userId)->where('status', 0)->count();

        // Left team counts
        // Counts are aggregated inside the tree query. Passing the leg back as
        // whereIn(...) meant several thousand placeholders per count, twice per
        // leg.
        $leftTeamIds = $this->getLeftTeamIds($userId);
        $totalLeftTeam = count($leftTeamIds);
        $leftStatusCounts = \App\Helpers\TreeHelper::legStatusCounts($userId, 1);
        $activeLeftTeam = $leftStatusCounts[1] ?? 0;
        $inactiveLeftTeam = $leftStatusCounts[0] ?? 0;

        // Right team counts
        $rightTeamIds = $this->getRightTeamIds($userId);
        $totalRightTeam = count($rightTeamIds);
        $rightStatusCounts = \App\Helpers\TreeHelper::legStatusCounts($userId, 2);
        $activeRightTeam = $rightStatusCounts[1] ?? 0;
        $inactiveRightTeam = $rightStatusCounts[0] ?? 0;
        // પર્સનલ લેફ્ટ અને રાઇટ એક્ટિવ ડાયરેક્ટ્સ
        $totalLeftDirects = User::where('sponsor_id', $user->username)
            ->where('position', 1)
            ->where('status', 1)
            ->count();

        $totalRightDirects = User::where('sponsor_id', $user->username)
            ->where('position', 2)
            ->where('status', 1)
            ->count();


        $order = Order::where('u_code', $userId)->where('tx_type', 'product')->latest('added_on')->first();

        // ૧. ટોટલ સેલ્સ (યુઝરે કરેલી કુલ ખરીદી)
        $totalPurchase = Order::where('u_code', $userId)
            ->where('tx_type', 'product')
            ->where('status', 2) // Delivered
            ->sum('order_amount');

        // ૨. કેન્સલ ઓર્ડરની સંખ્યા
        $canceledOrders = Order::where('u_code', $userId)
            ->where('tx_type', 'product')
            ->where('status', 3) // Cancelled
            ->count();

  
        $totalBV = Order::where('u_code', $userId)
            ->where('tx_type', 'product')
            ->where('status', 2) // Delivered
            ->sum('order_bv');;

        $recentPurchases = OrderItem::with('product')
            ->where('u_code', $userId)
            ->latest('added_on')
            ->take(4)
            ->get();

   

$matchingTypes = ['topup', 'retopup'];
$cronPrediction = [];

foreach ($matchingTypes as $type) {
    // પેર વેલ્યુ અને પેર ઇન્કમ (તમારા ક્રોનના પેરામીટર્સ મુજબ)
    $pairValue = ($type == 'topup') ? 10 : 10;
    $pairIncome = ($type == 'topup') ? 100 : 150;
    $capping = ($type == 'topup') ? 200000 : 0;
    $source = ($type == 'topup') ? 'binary_topup' : 'binary_retopup';

    // ૧. કુલ ટીમ બિઝનેસ (Original + Admin Power)
    $origLeft = \App\Helpers\UserHelper::teamBusinessVolumeByType($userId, 1, $type) ?? 0;
    $origRight = \App\Helpers\UserHelper::teamBusinessVolumeByType($userId, 2, $type) ?? 0;

    $adminLeft = \App\Models\AdminPowerBusiness::where('user_id', $userId)->where('matching_type', $type)->where('position', 1)->sum('bv_amount') ?? 0;
    $adminRight = \App\Models\AdminPowerBusiness::where('user_id', $userId)->where('matching_type', $type)->where('position', 2)->sum('bv_amount') ?? 0;

    $totalLeft = $origLeft + $adminLeft;
    $totalRight = $origRight + $adminRight;

    // ૨. ક્રોન લોજિક મુજબ ઓલરેડી વપરાયેલો (Matched) બિઝનેસ મેળવો
    $usedPairs = \App\Helpers\UserHelper::benPairsByType($userId, $type) ?? 0;
    $usedBusiness = $usedPairs * $pairValue;

    // ૩. હાલમાં બંને બાજુ બાકી રહેતો કરંટ બિઝનેસ (Current Available Business)
    // ક્રોન ચાલ્યા વગર યુઝરનો લેફ્ટ/રાઈટમાં નેટ કેટલો બિઝનેસ પડ્યો છે તે
    $currentAvailableLeft = $totalLeft - $usedBusiness;
    $currentAvailableRight = $totalRight - $usedBusiness;

    $currentAvailableLeft = $currentAvailableLeft > 0 ? $currentAvailableLeft : 0;
    $currentAvailableRight = $currentAvailableRight > 0 ? $currentAvailableRight : 0;

    // ૪. નેક્સ્ટ ક્રોન માટે એલિજિબલ બિઝનેસ અને પેર પ્રેડિક્શન
    $matchingBusiness = min($totalLeft, $totalRight);
    $eligibleBusiness = $matchingBusiness - $usedBusiness;
    $eligibleBusiness = $eligibleBusiness > 0 ? $eligibleBusiness : 0;

    $predictedPairs = floor($eligibleBusiness / $pairValue);
    $predictedIncome = $predictedPairs * $pairIncome;

    // ૫. ટોપઅપ માટે કેપિંગ લિમિટ ચેક
    if ($type == 'topup' && $capping > 0 && $predictedIncome > $capping) {
        $predictedIncome = $capping;
        $predictedPairs = floor($predictedIncome / $pairIncome);
    }

    // ૬. આ મેચિંગ થયા પછી નેક્સ્ટ ક્રોન જે કેરી ફોરવર્ડ છોડશે તે
    $matchedBusiness = $predictedPairs * $pairValue;
    $nextLeftCarry = $currentAvailableLeft - $matchedBusiness;
    $nextRightCarry = $currentAvailableRight - $matchedBusiness;

    $cronPrediction[$type] = [
        'available_left'   => $currentAvailableLeft,
        'available_right'  => $currentAvailableRight,
        'predicted_pairs'  => $predictedPairs,
        'predicted_income' => $predictedIncome,
        'next_left_carry'  => $nextLeftCarry,
        'next_right_carry' => $nextRightCarry,
    ];
}



        /*
        --------------------------------
        ACTIVE RIGHT DIRECTS
        --------------------------------
        */
        
        $activeRightDirects = User::where('sponsor_id', $user->username)
            ->where('status', 1)
            ->where('position', 2)
            ->count();
        
        
        /*
        --------------------------------
        ACTIVE LEFT DIRECTS
        --------------------------------
        */
        
        $activeLeftDirects = User::where('sponsor_id', $user->username)
            ->where('status', 1)
            ->where('position', 1)
            ->count();
                
                
// --- BINARY BUSINESS SUMMARY LIVE CALCULATION ---
$txTypes = ['topup', 'retopup'];
$binaryStats = [];

foreach ($txTypes as $type) {
    // ઓરિજિનલ ટીમ બિઝનેસ
    $origLeft = UserHelper::teamBusinessVolumeByType($userId, 1, $type) ?? 0;
    $origRight = UserHelper::teamBusinessVolumeByType($userId, 2, $type) ?? 0;

    // એડમિન પાવર બિઝનેસ
    $adminLeft = AdminPowerBusiness::where('user_id', $userId)->where('matching_type', $type)->where('position', 1)->sum('bv_amount') ?? 0;
    $adminRight = AdminPowerBusiness::where('user_id', $userId)->where('matching_type', $type)->where('position', 2)->sum('bv_amount') ?? 0;

    // ટોટલ બિઝનેસ (Original + Admin Power)
    $totalLeft = $origLeft + $adminLeft;
    $totalRight = $origRight + $adminRight;

    // આ ટાઈપની હિસ્ટ્રીમાંથી ઓલરેડી મેચ થયેલો કુલ બિઝનેસ અને કેરી ફોરવર્ડ મેળવો
    // ક્રોન કમાન્ડ મુજબ c23 (Left Carry) અને c24 (Right Carry) વોલેટમાં અપડેટ થાય છે, 
    // પરંતુ તે કરંટ સેશન માટે જ હોય છે. લાઈવ અને હિસ્ટોરિકલ સ્ટેટ્સ માટે MatchingBonusHistory બેસ્ટ રહેશે.
    $totalMatchedBusiness = MatchingBonusHistory::where('user_id', $userId)->where('matching_type', $type)->sum('matched_business') ?? 0;
    $totalMatchedPairs = MatchingBonusHistory::where('user_id', $userId)->where('matching_type', $type)->sum('matched_pairs') ?? 0;
    $totalEarnedBonus = MatchingBonusHistory::where('user_id', $userId)->where('matching_type', $type)->sum('bonus_amount') ?? 0;

    // ક્રોનની ગણતરી મુજબ છેલ્લો કેરી ફોરવર્ડ
    $lastHistory = MatchingBonusHistory::where('user_id', $userId)->where('matching_type', $type)->latest('id')->first();
    $carryLeft = $lastHistory ? $lastHistory->left_flush : $totalLeft;
    $carryRight = $lastHistory ? $lastHistory->right_flush : $totalRight;

    $binaryStats[$type] = [
        'total_left' => $totalLeft,
        'total_right' => $totalRight,
        'matched_business' => $totalMatchedBusiness,
        'matched_pairs' => $totalMatchedPairs,
        'earned_bonus' => $totalEarnedBonus,
        'carry_left' => $carryLeft,
        'carry_right' => $carryRight,
    ];
}

// Compact ફંક્શનમાં 'binaryStats' વેરીએબલ ઉમેરી દેવો
        
        

        return view('user.dashboard.index', compact(
            'user',
            'orders',
            'pendingWithdrawal',
            'totalWithdrawal',
            'paidWithdrawal',
            'rejectedWithdrawal',
            'userwallet',
            'totalamount',
            'teamwallets',
            'wallets',
            'RegistrationType',
            'totalincome',
            'incometype',
            'rank',
            'popupNotices',
            'marqueeNotices',
            'totalincomegss',
            'status_invesment',
            'binaryStats',
            // team section
            'teamtype',
            'totalDirects',
            'activeDirects',
            'inactiveDirects',
          
            'totalPurchase',
            'canceledOrders',
            'totalBV',
            'packageBv',
            'order',
            'recentPurchases',


'cronPrediction', 


//binary team section
            'activeLeftDirects',
            'activeRightDirects',
            'totalLeftTeam',
            'activeLeftTeam',
            'inactiveLeftTeam',
            'totalRightTeam',
            'activeRightTeam',
            'inactiveRightTeam',
            'totalRightDirects',
            'totalLeftDirects'




        ));
    }
    /**
     * Get all user IDs in the left team (including direct left_id user + their generations).
     */
    protected function getLeftTeamIds(int $userId): array
    {
        // Resolved through parent_id/position, which is the placement the
        // binary-matching income also uses. The old left_id/right_id walk
        // under-counted wherever those denormalised columns had drifted.
        return \App\Helpers\TreeHelper::legIds($userId, 1);
    }
    /**
     * Get all user IDs in the right team (including direct right_id user + their generations).
     */
    protected function getRightTeamIds(int $userId): array
    {
        return \App\Helpers\TreeHelper::legIds($userId, 2);
    }
    private function getDownlineIds($userId)
    {
        return collect(\App\Helpers\TreeHelper::descendantIds((int) $userId));
    }
    public function admindashboard()
    {
        // Registration type
        $RegistrationType = AdvanceInfo::where('label', 'reg_type')->value('value');
        $users = User::where('role', 'user');
        $userIds = $users->pluck('id');
        $totalUsers = $users->count();
        $lastWeekUsers = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $activeUsers = User::where('status', 1)->where('role', 'user')->count();
        $pendingUsers = User::where('status', 0)->where('role', 'user')->count();
        $ordersQuery = Order::whereIn('tx_type', ['topup', 'retopup']);
        // Total investment amount
        $totalinvestmentamount = $ordersQuery->sum('order_amount');
        // ✅ Today's investment using `added_on`
        $todayInvestment = (clone $ordersQuery)
            ->whereDate('added_on', Carbon::today())
            ->sum('order_amount');
        // ✅ Yesterday's investment
        $yesterdayInvestment = (clone $ordersQuery)
            ->whereDate('added_on', Carbon::yesterday())
            ->sum('order_amount');
        // ✅ Last 7 days investment
        $lastWeekInvestment = (clone $ordersQuery)
            ->where('added_on', '>=', Carbon::now()->subDays(7))
            ->sum('order_amount');
        $totalincome = Transaction::where('tx_type', 'income')->sum('amount');
        $pendingWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 0)->sum('amount');
        $paidWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 1)->sum('amount');
        $rejectedWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 2)->sum('amount');
        $totalwithdrawal = $pendingWithdrawal + $rejectedWithdrawal + $paidWithdrawal;
        $incometypes = WalletType::where('wallet_type', 'income')
            ->where($RegistrationType, '1')
            ->where('status', true)
            ->get();
        $incomeSums = [];
        foreach ($incometypes as $type) {
            $column = $type->wallet_column;
            $incomeSums[$column] = UserWallet::sum($column); // Sum across all users
        }
        return view('admin.dashboard.index', compact(
            'totalUsers',
            'lastWeekUsers',
            'activeUsers',
            'pendingUsers',
            'totalinvestmentamount',
            'totalincome',
            'todayInvestment',
            'yesterdayInvestment',
            'lastWeekInvestment',
            'pendingWithdrawal',
            'paidWithdrawal',
            'rejectedWithdrawal',
            'totalwithdrawal',
            'incometypes',
            'incomeSums',
        ));
    }
    public function superadmindashboard()
    {
        // Registration type
        $RegistrationType = AdvanceInfo::where('label', 'reg_type')->value('value');
        $users = User::where('role', 'user');
        $userIds = $users->pluck('id');
        $totalUsers = $users->count();
        $lastWeekUsers = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $activeUsers = UserWallet::whereIn('u_code', $userIds)->where('active_id', '!=', 0)->count();
        $pendingUsers = UserWallet::whereIn('u_code', $userIds)->where('active_id', 0)->count();
        $ordersQuery = Order::whereIn('tx_type', ['topup', 'retopup']);
        // Total investment amount
        $totalinvestmentamount = $ordersQuery->sum('order_amount');
        // ✅ Today's investment using `added_on`
        $todayInvestment = (clone $ordersQuery)
            ->whereDate('added_on', Carbon::today())
            ->sum('order_amount');
        // ✅ Yesterday's investment
        $yesterdayInvestment = (clone $ordersQuery)
            ->whereDate('added_on', Carbon::yesterday())
            ->sum('order_amount');
        // ✅ Last 7 days investment
        $lastWeekInvestment = (clone $ordersQuery)
            ->where('added_on', '>=', Carbon::now()->subDays(7))
            ->sum('order_amount');
        $totalincome = Transaction::where('tx_type', 'income')->sum('amount');
        $pendingWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 0)->sum('amount');
        $paidWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 1)->sum('amount');
        $rejectedWithdrawal = Transaction::where('tx_type', 'withdrawal')->where('status', 2)->sum('amount');
        $totalwithdrawal = $pendingWithdrawal + $rejectedWithdrawal + $paidWithdrawal;
        $incometypes = WalletType::where('wallet_type', 'income')
            ->where($RegistrationType, '1')
            ->where('status', true)
            ->get();
        $incomeSums = [];
        foreach ($incometypes as $type) {
            $column = $type->wallet_column;
            $incomeSums[$column] = UserWallet::sum($column); // Sum across all users
        }
        return view('superadmin.dashboard.index', compact(
            'totalUsers',
            'lastWeekUsers',
            'activeUsers',
            'pendingUsers',
            'totalinvestmentamount',
            'totalincome',
            'todayInvestment',
            'yesterdayInvestment',
            'lastWeekInvestment',
            'pendingWithdrawal',
            'paidWithdrawal',
            'rejectedWithdrawal',
            'totalwithdrawal',
            'incometypes',
            'incomeSums',
        ));
    }
    
        public function recentbinaryData(Request $request)
    {
        $user = auth()->user();
        $query = Transaction::where('u_code', $user->id)
            ->where('debit_credit', 'credit') // Filter only earnings
            ->whereIn('source', ['binary', 'binary_topup', 'binary_retopup'])
            ->where('tx_type', 'income')
            ->latest('added_on')
            ->take(4);
        return DataTables::of($query)
            ->editColumn('added_on', function ($row) {
                return \Carbon\Carbon::parse($row->added_on)->format('d M Y, h:i A');
            })
            ->make(true);
    }
    
    
    
    public function recentEarningsData(Request $request)
    {
        $user = auth()->user();
        $query = Transaction::where('u_code', $user->id)
            ->where('debit_credit', 'credit') // Filter only earnings
            ->where('tx_type', 'income')
            ->latest('added_on')
            ->take(5);
        return DataTables::of($query)
            ->editColumn('added_on', function ($row) {
                return \Carbon\Carbon::parse($row->added_on)->format('d M Y, h:i A');
            })
            ->make(true);
    }
    
    
    public function recentUserData(Request $request)
    {
        $query = User::where('role', 'user')->latest('created_at')->take(5);
        return DataTables::of($query)
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d M Y, h:i A');
            })
            ->make(true);
    }
}
