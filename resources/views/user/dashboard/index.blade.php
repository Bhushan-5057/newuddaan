@extends('user.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .card .list-group-item {
            border: none;
        }

        .customicon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0f626a, #0ab964);
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .text-gradient {
            font-weight: bold;
        }

        .package-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .package-amount {
            font-size: 1.5rem;
        }

        .package-divider {
            border: 1px solid;
            width: auto;
        }
    </style>

    @if (session('showPopup') && $popupNotices->count())
        @include('user.dashboard.showPopup')
    @endif

    @if ($marqueeNotices->count())
        @include('user.dashboard.marqueeNotices')
    @endif

    <!-- Right Card -->
    <div class="row mb-3">

        <!-- Income Boxes (col-lg-8) -->
        @if (auth()->user()->hasPermission('Dashboard Income', 'can_view'))
            <div class="row g-3">

                {{-- Left Side Income Cards --}}
                <div class="col-lg-8">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                
                        @php
                            $colors = [
                                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                                'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                                'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                                'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
                            ];
                
                            $icons = [
                                asset('assets/icon/1.png'),
                                asset('assets/icon/5.png'),
                                asset('assets/icon/6.png'),
                                asset('assets/icon/2.png'),
                                asset('assets/icon/7.png'),
                                asset('assets/icon/8.png'),
                                asset('assets/icon/4.png'),
                                asset('assets/icon/10.png'),
                            ];
                
                            $colorIndex = 0;
                            $iconsIndex = 0;
                        @endphp
                
                        {{-- Dynamic Income Types --}}
                        @foreach ($incometype as $income)
                            @php
                                $slug = $income->wallet_column;
                                $source = $income->slug;

                                $bgColor = $colors[$colorIndex % count($colors)];
                                $colorIndex++;

                                $icon = $icons[$iconsIndex % count($icons)];
                                $iconsIndex++;

                                $amount = $userwallet->{$income->wallet_column} ?? 0;

                                $isBinary = str_contains($source, 'binary');
                                $lastWeeklyIncome = 0;

                                if ($isBinary) {
                                    $lastWeeklyIncome = \DB::table('transaction')
                                        ->where('u_code', auth()->id())
                                        ->where('source', $source)
                                        ->where('tx_type', 'income')
                                        ->latest('id')
                                        ->value('amount') ?? 0;
                                }
                            @endphp

                            <div class="col">
                                <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden"
                                    style="background: {{ $bgColor }}; min-height: 110px;">
                                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                                        <div class="pe-2 ">
                                            <h6 class="mb-1 fw-semibold small" style="letter-spacing: 0.5px; opacity: 0.9;">
                                                {{ strtoupper($income->name) }}
                                            </h6>
                                            <h4 class="mb-0 fw-bold text-nowrap">
                                                {{ company_info('currency', '₹') . ' ' . rtrim(rtrim(number_format($amount, 3, '.', ''), '0'), '.') }}
                                            </h4>
                                            
                                            @if($isBinary && $lastWeeklyIncome > 0)
                                                <small class="d-block mt-1 text-nowrap" style="font-size: 0.75rem; opacity: 0.85;">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i>
                                                    Last Week Income: {{ company_info('currency', '₹') . ' ' . rtrim(rtrim(number_format($lastWeeklyIncome, 3, '.', ''), '0'), '.') }}
                                                </small>
                                            @endif
                                        </div>

                                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <img src="{{ $icon }}" alt="icon" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @php
                            $colorIndex = 0; 
                            $walletIcons = [
                                'main_wallet' => 'fa-solid fa-wallet',
                                'roi_wallet' => 'fa-solid fa-sack-dollar',
                                'level_wallet' => 'fa-solid fa-users',
                                'salary_wallet' => 'fa-solid fa-briefcase',
                                'reward_wallet' => 'fa-solid fa-gift',
                            ];
                        @endphp
                        
                        {{-- Total Income Card --}}
                        <div class="col">
                            <div class="card shadow-sm border-0 rounded-4 h-100  overflow-hidden"
                                style="background: linear-gradient(135deg, #141e30 0%, #243b55 100%); min-height: 110px;">
                                <div class="card-body d-flex justify-content-between align-items-center p-3">
                                    <div class="pe-2">
                                        <h6 class="mb-1  fw-semibold small" style="letter-spacing: 0.5px;">
                                            TOTAL INCOME
                                        </h6>
                                        <h4 class="mb-0 fw-bold text-nowrap">
                                            {{ company_info('currency', '₹') }} {{ rtrim(rtrim(number_format($totalincomegss, 3, '.', ''), '0'), '.') }}
                                        </h4>
                                    </div>
                
                                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center  bg-opacity-25 rounded-3 p-2" style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-chart-line fs-3 "></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        {{-- Dynamic Wallets --}}
                        @if (auth()->user()->hasPermission('Wallets', 'can_view'))
                            @foreach ($wallets as $wallet)
                                @php
                                    $walletValue = $userwallet->{$wallet->wallet_column} ?? 0;
                                    $bgColor = $colors[$colorIndex % count($colors)];
                                    $colorIndex++;
                                    $iconClass = $walletIcons[$wallet->wallet_column] ?? 'fa-solid fa-coins';
                                @endphp
                
                                <div class="col">
                                    <div class="card shadow-sm border-0 rounded-4 h-100  overflow-hidden"
                                        style="background: {{ $bgColor }}; min-height: 110px;">
                                        <div class="card-body d-flex justify-content-between align-items-center p-3">
                                            <div class="pe-2">
                                                <h6 class="mb-1  fw-semibold small" style="letter-spacing: 0.5px;">
                                                    {{ strtoupper($wallet->name) }}
                                                </h6>
                                                <h4 class="mb-0 fw-bold text-nowrap">
                                                    {{ company_info('currency', '₹') }} {{ rtrim(rtrim(number_format($walletValue, 3, '.', ''), '0'), '.') }}
                                                </h4>
                                            </div>
                
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center  bg-opacity-25 rounded-3 p-2" style="width: 50px; height: 50px;">
                                                <i class="{{ $iconClass }} fs-3 "></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                
                    </div>
                
                    <div class=" mb-4">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <h4 class="card-title mb-3">Recent Matching History</h4>
                                <div class="table-responsive">
                                    <table class="table" id="RecentBinaryTable">
                                        <thead class="table-primary">
                                            <tr>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Remark</th>
                                                <th scope="col">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Dynamic rows populated via DataTables --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side User Panel --}}
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm" style="background:#1f2430;">
                        <div class="card-body p-3">
                            {{-- Profile Section --}}
                            <div class="mb-3">
                                {{-- Profile Image Center --}}
                                <div class="text-center mb-3">
                                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('assets/img/avatars/1.png') }}"
                                        alt="Profile Image"
                                        class="rounded-circle border border-2 border-light shadow-sm object-fit-cover"
                                        width="78" height="78">
                                </div>

                                {{-- User Info --}}
                                <div class="d-flex flex-column gap-2">
                                    {{-- User --}}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                            <i class="fa-solid fa-user text-info me-1"></i>
                                            User
                                        </div>
                                        <div class=" fw-bold small">
                                            {{ Auth::user()->name }}
                                        </div>
                                    </div>

                                    {{-- Username --}}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                            Distributor ID
                                        </div>
                                        <div class=" small">
                                            {{ Auth::user()->username }}
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                           Username
                                        </div>
                                        <div class=" small">
                                            {{ Auth::user()->unique_name }}
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    @php
                                        // A package of at least 1 BV but under 100 counts as Pending:
                                        // the user has started but has not reached the activation
                                        // threshold yet. 100+ BV, or an account already marked
                                        // active, shows Active; anything else Inactive.
                                        $pkgBv = $packageBv ?? 0;

                                        if ($pkgBv >= 1 && $pkgBv < 100) {
                                            $statusLabel = 'Pending';
                                            $statusClass = 'bg-warning text-dark';
                                        } elseif ($pkgBv >= 100 || Auth::user()->status == 1) {
                                            $statusLabel = 'Active';
                                            $statusClass = 'bg-success';
                                        } else {
                                            $statusLabel = 'Inactive';
                                            $statusClass = 'bg-danger';
                                        }
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                            Status
                                        </div>
                                        <div>
                                            <span class="badge rounded-pill {{ $statusClass }}"
                                                style="font-size:10px;">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Rank --}}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                            Rank
                                        </div>
                                        <div class=" small fw-bold">
                                            {{ Auth::user()->rank ?? 'N/A' }}
                                        </div>
                                    </div>

                                    {{-- Package --}}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class=" opacity-75 small fw-semibold">
                                            My Package
                                        </div>
                                        {{-- Shown in BV, not currency: the package threshold this
                                             card reports against is a BV figure. --}}
                                        <div class="fw-bold text-success small">
                                            <i class="fa-solid fa-box me-1"></i>
                                            {{ rtrim(rtrim(number_format($pkgBv, 2, '.', ''), '0'), '.') }} BV
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="border-top border-secondary my-3 opacity-50"></div>

                            {{-- Team Stats --}}
                            <div class="d-flex flex-column gap-2">
                                @php
                                    $icons = [
                                        'fas fa-user',
                                        'fas fa-user-check',
                                        'fas fa-user-times',
                                        'fa-solid fa-sitemap',
                                        'fas fa-users',
                                        'fa-solid fa-sack-dollar',
                                        'fa-solid fa-briefcase',
                                        'fa-solid fa-gift',
                                        'fa-solid fa-coins',
                                    ];
                                @endphp

                                @foreach ($teamtype as $key => $income)
                                    @php
                                        $icon = $icons[$key % count($icons)];
                                        $amount = $userwallet->{$income->wallet_column} ?? 0;
                                    @endphp

                                    {{-- Stat Card --}}
                                    <div class="rounded-4 px-3 py-2" style="background:#4b4a68;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- Left --}}
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Icon --}}
                                                <div class="d-flex align-items-center justify-content-center"
                                                    style="width:38px; height:38px; border-radius:12px; background:linear-gradient(135deg,#25d0ff 0%,#37b6ff 100%); color:white; flex-shrink:0;">
                                                    <i class="{{ $icon }}" style="font-size:14px;"></i>
                                                </div>
                                                {{-- Title --}}
                                                <div>
                                                    <div class="fw-semibold " style="font-size:13px; line-height:1.2;">
                                                        {{ strtoupper($income->name) }}
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Right Value --}}
                                            <div class="fw-bold" style="color:#00e0ff; font-size:14px;">
                                                {{ $amount }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="rounded-4 px-3 py-2" style="background:#4b4a68;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        {{-- Left --}}
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Icon --}}
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="width:38px; height:38px; border-radius:12px; background:linear-gradient(135deg,#25d0ff 0%,#37b6ff 100%); color:white; flex-shrink:0;">
                                                <i class="fa-solid fa-sitemap" style="font-size:14px;"></i>
                                            </div>
                                            {{-- Title --}}
                                            <div>
                                                <div class="fw-semibold " style="font-size:13px; line-height:1.2;">
                                                    LEFT TEAM
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Right Value --}}
                                        <div class="fw-bold" style="color:#00e0ff; font-size:14px;">
                                            {{ $totalLeftTeam }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="rounded-4 px-3 py-2" style="background:#4b4a68;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        {{-- Left --}}
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Icon --}}
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="width:38px; height:38px; border-radius:12px; background:linear-gradient(135deg,#25d0ff 0%,#37b6ff 100%); color:white; flex-shrink:0;">
                                                <i class="fa-solid fa-sitemap" style="font-size:14px;"></i>
                                            </div>
                                            {{-- Title --}}
                                            <div>
                                                <div class="fw-semibold " style="font-size:13px; line-height:1.2;">
                                                    RIGHT TEAM
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Right Value --}}
                                        <div class="fw-bold" style="color:#00e0ff; font-size:14px;">
                                            {{ $totalRightTeam }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="rounded-4 px-3 py-2" style="background:#4b4a68;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        {{-- Left --}}
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Icon --}}
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="width:38px; height:38px; border-radius:12px; background:linear-gradient(135deg,#25d0ff 0%,#37b6ff 100%); color:white; flex-shrink:0;">
                                                <i class="fa-solid fa-sitemap" style="font-size:14px;"></i>
                                            </div>
                                            {{-- Title --}}
                                            <div>
                                                <div class="fw-semibold " style="font-size:13px; line-height:1.2;">
                                                    TOTAL TEAM
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Right Value --}}
                                        <div class="fw-bold" style="color:#00e0ff; font-size:14px;">
                                            {{ $totalRightTeam  + $totalLeftTeam}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (auth()->user()->hasPermission('Wallets view', 'can_view'))
        <div class="col-lg-12">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @php
                    $colors = [
                        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                        'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                        'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
                    ];

                    $icons = [
                        'main_wallet' => 'fa-solid fa-wallet',
                        'roi_wallet' => 'fa-solid fa-sack-dollar',
                        'level_wallet' => 'fa-solid fa-users',
                        'salary_wallet' => 'fa-solid fa-briefcase',
                        'reward_wallet' => 'fa-solid fa-gift',
                    ];

                    $colorIndex = 0;
                @endphp

                {{-- Total Income --}}
                <div class="col">
                    <div class="card shadow-sm border-0 rounded-4 h-100  overflow-hidden"
                        style="background: linear-gradient(135deg, #141e30 0%, #243b55 100%);">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 ">TOTAL INCOME</h6>
                                <h4 class="mb-2 fw-bold">
                                    {{ company_info('currency', '₹') }}
                                    {{ rtrim(rtrim(number_format($totalincomegss, 3, '.', ''), '0'), '.') }}
                                </h4>
                            </div>
                            <div class="icon-box">
                                <i class="fa-solid fa-chart-line fs-1  opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dynamic Wallets --}}
                @foreach ($wallets as $wallet)
                    @php
                        $walletValue = $userwallet->{$wallet->wallet_column} ?? 0;
                        $bgColor = $colors[$colorIndex % count($colors)];
                        $colorIndex++;
                        $icon = $icons[$wallet->wallet_column] ?? 'fa-solid fa-coins';
                    @endphp

                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 h-100  overflow-hidden"
                            style="background: {{ $bgColor }};">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 ">{{ strtoupper($wallet->name) }}</h6>
                                    <h4 class="mb-0 fw-bold">
                                        {{ company_info('currency', '₹') }}
                                        {{ rtrim(rtrim(number_format($walletValue, 3, '.', ''), '0'), '.') }}
                                    </h4>
                                </div>
                                <div class="icon-box">
                                    <i class="{{ $icon }} fs-1 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-3">
            @php
                $ranks = \App\Helpers\RankRewardHelper::getRanks();
                $user = auth()->user();

                $teamLeftBV = \App\Helpers\UserHelper::teamBusinessVolumeByType($user->id, 1, 'topup') ?? 0;
                $teamRightBV = \App\Helpers\UserHelper::teamBusinessVolumeByType($user->id, 2, 'topup') ?? 0;

                $adminPowerLeft = \App\Models\AdminPowerBusiness::where('user_id', $user->id)
                    ->where('matching_type', 'topup')
                    ->where('position', 1)
                    ->sum('bv_amount') ?? 0;

                $adminPowerRight = \App\Models\AdminPowerBusiness::where('user_id', $user->id)
                    ->where('matching_type', 'topup')
                    ->where('position', 2)
                    ->sum('bv_amount') ?? 0;

                $totalLeftAchieved = $teamLeftBV + $adminPowerLeft;
                $totalRightAchieved = $teamRightBV + $adminPowerRight;
                
                $accumulatedLeftRequired = 0;
                $accumulatedRightRequired = 0;
                $runningRank = null;

                foreach($ranks as $rank) {
                    $alreadyAchieved = \App\Models\RankHistory::where('user_id', $user->id)
                        ->where('rank', $rank['name'])
                        ->exists();

                    $accumulatedLeftRequired += $rank['left'];
                    $accumulatedRightRequired += $rank['right'];

                    if(!$alreadyAchieved) {
                        $runningRank = $rank;
                        $runningRank['target_left'] = $accumulatedLeftRequired;
                        $runningRank['target_right'] = $accumulatedRightRequired;
                        $runningRank['left_percent'] = $accumulatedLeftRequired > 0 ? min(100, ($totalLeftAchieved / $accumulatedLeftRequired) * 100) : 0;
                        $runningRank['right_percent'] = $accumulatedRightRequired > 0 ? min(100, ($totalRightAchieved / $accumulatedRightRequired) * 100) : 0;
                        break;
                    }
                }
            @endphp

            @if($runningRank)
                @php
                    $cleanReward = preg_replace('/\s*\([^)]*\)/', '', $runningRank['reward']);
                    $cleanBonanza = preg_replace('/\s*\([^)]*\)/', '', $runningRank['bonanza']);
                @endphp

                <div class="card card-body border border-warning shadow-sm rounded-3">
                    <div class="card-header bg-info py-3 text-white">
                        <h5 class="mb-0 fw-bold text-center text-uppercase">
                            <i class="fas fa-trophy me-2"></i> Next  Rank: {{ $runningRank['name'] }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 text-center">
                            <div class="col-md-6 border-end">
                                <p class="text-secondary fw-semibold mb-1 text-uppercase small">Left Node Status</p>
                                <h3 class="fw-bold mb-3 text-dark">
                                    {{ number_format($totalLeftAchieved) }} <span class="fs-6 text-muted">/ {{ number_format($runningRank['target_left']) }} BV</span>
                                </h3>
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">Progress</span>
                                    <span class="fw-bold text-success">{{ number_format($runningRank['left_percent'], 0) }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 14px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         style="width: {{ $runningRank['left_percent'] }}%;" 
                                         aria-valuenow="{{ $runningRank['left_percent'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p class="text-secondary fw-semibold mb-1 text-uppercase small">Right Node Status</p>
                                <h3 class="fw-bold mb-3 text-dark">
                                    {{ number_format($totalRightAchieved) }} <span class="fs-6 text-muted">/ {{ number_format($runningRank['target_right']) }} BV</span>
                                </h3>
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">Progress</span>
                                    <span class="fw-bold text-primary">{{ number_format($runningRank['right_percent'], 0) }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 14px;">
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         style="width: {{ $runningRank['right_percent'] }}%;" 
                                         aria-valuenow="{{ $runningRank['right_percent'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-3 text-muted">
                        <div class="row g-2 text-center small">
                            @if($runningRank['bonus'] > 0)
                                <div class="col-md-4">
                                    <span class="text-muted d-block">Next Bonus</span>
                                    <span class="fw-bold text-success fs-6">₹{{ number_format($runningRank['bonus']) }}</span>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <span class="text-muted d-block">Next Bonus</span>
                                    <span class="fw-bold text-secondary fs-6">-</span>
                                </div>
                            @endif
                            
                            <div class="col-md-4 border-start border-end">
                                <span class="text-muted d-block">Next Reward</span>
                                <span class="fw-bold text-uppercase text-dark fs-6">{{ $cleanReward }}</span>
                            </div>
                            
                            <div class="col-md-4">
                                <span class="text-muted d-block">Bonanza Benefit</span>
                                <span class="fw-medium text-dark fs-6">{{ $cleanBonanza }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!--<div class="alert alert-success text-center shadow-sm" role="alert">-->
                <!--    <i class="fas fa-check-circle me-2 fs-5"></i> -->
                <!--    <strong>Congratulations!</strong> You have achieved all available ranks!-->
                <!--</div>-->
            @endif
        </div>

        <!-- REFERRAL LINK SECTION - FIXED -->
    <!-- REFERRAL LINK SECTION - FIXED -->
<div class="col-md-12 mb-3">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">
                    <i class="fa-solid fa-link text-primary me-2"></i> Referral Link
                </h5>

                @if (company_info('binary_status') == 'yes')
                    <form action="{{ route('user.update.default.side') }}" method="POST">
                        @csrf
                        <div class="btn-group" role="group">
                            <button type="submit" name="default_side" value="left"
                                class="btn btn-outline-primary {{ auth()->user()->default_side == 'left' ? 'active' : '' }}">
                                Left
                            </button>
                            <button type="submit" name="default_side" value="right"
                                class="btn btn-outline-primary {{ auth()->user()->default_side == 'right' ? 'active' : '' }}">
                                Right
                            </button>
                            <button type="submit" name="default_side" value="auto"
                                class="btn btn-outline-primary {{ auth()->user()->default_side == 'auto' ? 'active' : '' }}">
                                Auto
                            </button>
                        </div>
                    </form>
                @endif

                <span class="badge bg-success">{{ Auth::user()->username }}</span>
            </div>

            @if (company_info('binary_status') == 'no')
                @php
                    $leftLink = url('/register?ref=' . auth()->user()->username . '&side=left');
                    $rightLink = url('/register?ref=' . auth()->user()->username . '&side=right');
                @endphp

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Left Side</label>
                        <div class="input-group">
                            <!-- Clickable Link Input -->
                            <a href="{{ $leftLink }}" class="form-control text-decoration-none text-primary" 
                               style="cursor: pointer; display: flex; align-items: center;"
                               target="_blank">
                                {{ $leftLink }}
                            </a>
                            <button class="btn btn-primary" type="button"
                                onclick="copyToClipboard('LeftRefLink')">
                                <i class="fa-solid fa-copy me-1"></i> Copy
                            </button>
                            <!-- Logout & Redirect Button -->
                            <button class="btn btn-secondary logout-redirect-btn" 
                                data-link="{{ $leftLink }}"
                                title="Logout and Open Link">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </button>
                        </div>
                        <!-- Hidden input for copy functionality -->
                        <input type="hidden" id="LeftRefLink" value="{{ $leftLink }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Right Side</label>
                        <div class="input-group">
                            <!-- Clickable Link Input -->
                            <a href="{{ $rightLink }}" class="form-control text-decoration-none text-primary" 
                               style="cursor: pointer; display: flex; align-items: center;"
                               target="_blank">
                                {{ $rightLink }}
                            </a>
                            <button class="btn btn-primary" type="button"
                                onclick="copyToClipboard('RightRefLink')">
                                <i class="fa-solid fa-copy me-1"></i> Copy
                            </button>
                            <!-- Logout & Redirect Button -->
                            <button class="btn btn-secondary logout-redirect-btn" 
                                data-link="{{ $rightLink }}"
                                title="Logout and Open Link">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </button>
                        </div>
                        <!-- Hidden input for copy functionality -->
                        <input type="hidden" id="RightRefLink" value="{{ $rightLink }}">
                    </div>
                </div>
            @else
                @php
                    $defaultLink = url('/register?ref=' . Auth::user()->username);
                @endphp
                <div class="input-group">
                    <!-- Clickable Link Input -->
                    <a href="{{ $defaultLink }}" class="form-control text-decoration-none text-primary" 
                       style="cursor: pointer; display: flex; align-items: center;"
                       target="_blank">
                        {{ $defaultLink }}
                    </a>
                    <button class="btn btn-primary d-flex align-items-center" type="button"
                        onclick="copyToClipboard('RefLink')">
                        <i class="fa-solid fa-copy me-1"></i> Copy
                    </button>
                    <!-- Logout & Redirect Button -->
                    <button class="btn btn-secondary d-flex align-items-center logout-redirect-btn" 
                        data-link="{{ $defaultLink }}"
                        title="Logout and Open Link">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </button>
                </div>
                <input type="hidden" id="RefLink" value="{{ $defaultLink }}">
            @endif

        </div>
    </div>
</div>

<!-- User Logout Form (Hidden) -->
<form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
    @csrf
</form>


    </div>

    <!-- User Logout Form (Hidden) - SINGLE FORM -->
    <form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Rest of your content... -->
    <div class="row">
        <div class="col-xl-4">
            <div class="row text-center">
                @if (auth()->user()->hasPermission('Payout Details', 'can_view'))
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <i class="fas fa-check-circle text-success fs-1 mb-2"></i>
                                <h5 class="fw-bold mb-1">Payout Released</h5>
                                <h4 class="text-success fw-bold mb-0">
                                    {{ company_info('currency', '$') }}
                                    {{ rtrim(rtrim(number_format($paidWithdrawal, 4, '.', ''), '0'), '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <i class="fas fa-hourglass-half text-warning fs-1 mb-2"></i>
                                <h5 class="fw-bold mb-1">Payout Pending</h5>
                                <h4 class="text-warning fw-bold mb-0">
                                    {{ company_info('currency', '$') }}
                                    {{ rtrim(rtrim(number_format($pendingWithdrawal, 4, '.', ''), '0'), '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (auth()->user()->hasPermission('Binary Business Summary', 'can_view'))
            <div class="col-xl-4">
                <div class="header-box d-flex justify-content-between align-items-center mb-3">
                    <h5>Business Summary</h5>
                    <span class="badge bg-primary">Live Stats</span>
                </div>
                
                <div class="card">
                    <div class="card-body p-3">
                        <ul class="nav nav-pills nav-justified mb-3" id="binaryTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active btn-sm" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab">Purchase</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-sm" id="repurchase-tab" data-bs-toggle="tab" data-bs-target="#repurchase" type="button" role="tab">RePurchase</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="binaryTabContent">
                            @foreach(['topup' => 'purchase', 'retopup' => 'repurchase'] as $key => $tabId)
                            <div class="tab-pane fade @if($key == 'topup') show active @endif" id="{{ $tabId }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-sm table-centered align-middle table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td><span class="text-muted f-s-13">Total Left BV</span></td>
                                                <td class="text-end fw-bold text-dark">{{ number_format($binaryStats[$key]['total_left']) }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-muted f-s-13">Total Right BV</span></td>
                                                <td class="text-end fw-bold text-dark">{{ number_format($binaryStats[$key]['total_right']) }}</td>
                                            </tr>
                                            <tr class="bg-light-success">
                                                <td><span class="fw-bold text-success f-s-13"><i class="fa-solid fa-handshake me-1"></i> Matched BV</span></td>
                                                <td class="text-end fw-bold text-success">{{ number_format($binaryStats[$key]['matched_business']) }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-muted f-s-13">Matched Pairs</span></td>
                                                <td class="text-end fw-bold text-warning">{{ $binaryStats[$key]['matched_pairs'] }} Pairs</td>
                                            </tr>
                                            <tr class="bg-light-info">
                                                <td><span class="fw-bold text-info f-s-13"><i class="fa-solid fa-arrows-left-right me-1"></i> Carry Forward</span></td>
                                                <td class="text-end f-s-12 text-info">
                                                    <strong>L:</strong> {{ number_format($binaryStats[$key]['carry_left']) }} | 
                                                    <strong>R:</strong> {{ number_format($binaryStats[$key]['carry_right']) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="fw-bold text-primary f-s-13"><i class="fa-solid fa-wallet me-1"></i> Total Income</span></td>
                                                <td class="text-end fw-bold text-primary">
                                                    {{ company_info('currency', '$') }}{{ number_format($binaryStats[$key]['earned_bonus'], 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <small class="text-muted italic"><i class="fa-solid fa-circle-info me-1"></i>Daily Closing</small>
                            <a href="{{ route('user.tree.view') }}" class="f-s-12 fw-bold text-primary text-decoration-none">
                                Binary Tree <i class="fa-solid fa-arrow-right-long ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasPermission('Binary Business Summary', 'can_view'))
            <div class="col-xl-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pb-0 pt-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-calculator text-primary me-2"></i>Next Payout Prediction</h5>
                        <span class="badge bg-light-success text-success px-2 py-1"><i class="fa-solid fa-wand-magic-sparkles me-1"></i>Live Estimation</span>
                    </div>
                    
                    <div class="card-body">
                        <ul class="nav nav-pills nav-justified mb-3 p-1 bg-light rounded" id="predictionTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold py-2 btn-sm" id="pred-purchase-tab" data-bs-toggle="tab" data-bs-target="#pred-purchase" type="button" role="tab">
                                    Purchase
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-2 btn-sm" id="pred-repurchase-tab" data-bs-toggle="tab" data-bs-target="#pred-repurchase" type="button" role="tab">
                                    RePurchase
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="predictionTabContent">
                            @foreach(['topup' => 'pred-purchase', 'retopup' => 'pred-repurchase'] as $key => $tabId)
                            <div class="tab-pane fade @if($key == 'topup') show active @endif" id="{{ $tabId }}" role="tabpanel">
                                <div class="row g-2 text-center mb-3">
                                    <div class="col-6">
                                        <div class="p-2 border rounded bg-light border-start-info">
                                            <small class="text-muted fw-bold d-block text-uppercase f-s-11">Available Left BV</small>
                                            <span class="fs-5 fw-bold text-dark font-monospace">{{ number_format($cronPrediction[$key]['available_left']) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded bg-light border-start-info">
                                            <small class="text-muted fw-bold d-block text-uppercase f-s-11">Available Right BV</small>
                                            <span class="fs-5 fw-bold text-dark font-monospace">{{ number_format($cronPrediction[$key]['available_right']) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="py-2 text-muted"><i class="fa-solid fa-link me-2 text-secondary"></i>Estimated Pairs</td>
                                                <td class="text-end py-2 fw-bold text-dark font-monospace">
                                                    {{ $cronPrediction[$key]['predicted_pairs'] }} Pairs
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 text-muted"><i class="fa-solid fa-share me-2 text-warning"></i>Next Carry Forward (L / R)</td>
                                                <td class="text-end py-2 fw-bold text-secondary font-monospace f-s-13">
                                                    L: {{ number_format($cronPrediction[$key]['next_left_carry']) }} | R: {{ number_format($cronPrediction[$key]['next_right_carry']) }}
                                                </td>
                                            </tr>
                                            <tr class="table-success-light border-top">
                                                <td class="py-2 fw-bold text-success"><i class="fa-solid fa-wallet me-2"></i>Predicted Income</td>
                                                <td class="text-end py-2 fw-bold text-success font-monospace fs-5">
                                                    {{ company_info('currency', '$') }}{{ number_format($cronPrediction[$key]['predicted_income'], 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <small class="text-muted italic"><i class="fa-solid fa-clock me-1"></i> This income is an estimate and will be credited upon the next weekly closing cron execution.</small>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasPermission('Left Team', 'can_view') || auth()->user()->hasPermission('Right Team', 'can_view'))
            <div class="col-xl-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Binary Team Activity</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="nav nav-pills nav-fill  rounded-pill p-1 mb-4" id="teamTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link rounded-pill active py-2" id="left-tab" data-bs-toggle="tab"
                                    data-bs-target="#leftPane" type="button">Left Team ({{ $totalLeftTeam }})</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill py-2" id="right-tab" data-bs-toggle="tab"
                                    data-bs-target="#rightPane" type="button">Right Team
                                    ({{ $totalRightTeam }})</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="leftPane">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3  text-center">
                                            <small class="text-muted d-block fw-bold">Active Direct</small>
                                            <span class="h6 mb-0 {{ $activeLeftDirects >= 1 ? 'text-success' : 'text-danger' }}">{{ $activeLeftDirects }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3  text-center">
                                            <small class="text-muted d-block fw-bold">Total Direct</small>
                                            <span class="h6 mb-0 ">{{ $totalLeftDirects }}</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('user.genealogy.left-team') }}" class="text-decoration-none mb-3 d-block">
                                    <div class="d-flex align-items-center p-3 border rounded-3 bg-soft-success">
                                        <div class="h-40 w-40 rounded-circle bg-success  d-flex-center shadow-sm">
                                            <i class="fa-solid fa-users"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <p class="mb-0 fw-bold  small">Active Team</p>
                                            <h6 class="mb-0 text-success fw-bold">{{ $activeLeftTeam }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="tab-pane fade" id="rightPane">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3  text-center">
                                            <small class="text-muted d-block fw-bold">Active Direct</small>
                                            <span class="h6 mb-0 {{ $activeRightDirects >= 1 ? 'text-success' : 'text-danger' }}">{{ $activeRightDirects }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3  text-center">
                                            <small class="text-muted d-block fw-bold">Total Direct</small>
                                            <span class="h6 mb-0 ">{{ $totalRightDirects }}</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('user.genealogy.right-team') }}" class="text-decoration-none mb-3 d-block">
                                    <div class="d-flex align-items-center p-3 border rounded-3 bg-soft-success">
                                        <div class="h-40 w-40 rounded-circle bg-success  d-flex-center shadow-sm">
                                            <i class="fa-solid fa-users"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <p class="mb-0 fw-bold  small">Active Team</p>
                                            <h6 class="mb-0 text-success fw-bold">{{ $activeRightTeam }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasPermission('Recent Earnings', 'can_view'))
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Recent Earnings</h4>
                        <div class="table-responsive">
                            <table class="table" id="RecentEarningsTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Remark</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dynamic rows populated via DataTables --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasPermission('product', 'can_view'))
            @include('user.dashboard.product')
        @endif
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#RecentEarningsTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 5,
                lengthChange: false,
                searching: false,
                paging: false,
                info: false,
                ajax: '{{ route('user.dashboard.recentEarningsData') }}',
                columns: [
                    { data: 'amount', name: 'amount', orderable: false },
                    { data: 'remark', name: 'remark', orderable: false },
                    { data: 'added_on', name: 'added_on', orderable: false },
                ],
                order: []
            });
        });

        $(document).ready(function() {
            $('#RecentBinaryTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 4,
                lengthChange: false,
                searching: false,
                paging: false,
                info: false,
                ajax: '{{ route('user.dashboard.recentbinaryData') }}',
                columns: [
                    { data: 'amount', name: 'amount', orderable: false },
                    { data: 'remark', name: 'remark', orderable: false },
                    { data: 'added_on', name: 'added_on', orderable: false },
                ],
                order: []
            });
        });


    </script>
    
    
<!-- JavaScript -->
<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        if (!copyText) return;
        
        const textToCopy = copyText.value || copyText.textContent;
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Referral link copied to clipboard.',
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            }).catch(() => {
                fallbackCopy(textToCopy);
            });
        } else {
            fallbackCopy(textToCopy);
        }
    }

    function fallbackCopy(text) {
        // Fallback method using a temporary input
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Referral link copied to clipboard.',
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Failed to copy the text!',
            });
        }
        document.body.removeChild(tempInput);
    }

    // Handle Logout & Redirect for referral links
    $(document).on('click', '.logout-redirect-btn', function(e) {
        e.preventDefault();
        
        const link = $(this).data('link');
        const button = $(this);
        
        // Show loading state
        button.html('<i class="fa-solid fa-spinner fa-spin"></i>');
        button.prop('disabled', true);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;

        // Logout the user via AJAX
        fetch('{{ route('user.logout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Logout failed');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to register page with referral data
                window.location.href = link;
            } else {
                throw new Error(data.message || 'Logout failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: Use traditional form submission
            const form = document.getElementById('user-logout-form');
            if (form) {
                // Store the link in a data attribute on the form
                form.dataset.redirectUrl = link;
                form.submit();
            } else {
                // If form doesn't exist, just redirect
                window.location.href = link;
            }
        });
    });

    // Handle form submission fallback for logout
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('user-logout-form');
        if (form && form.dataset.redirectUrl) {
            // This will be handled by the server-side redirect
        }
    });
</script>
@endsection