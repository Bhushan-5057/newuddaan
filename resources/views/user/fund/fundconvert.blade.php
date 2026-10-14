@extends('user.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>



   

    /* Glass Card Effect */
    .glass-card {
        background: var(--glass-bg-base);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid var(--glass-border-base);
        box-shadow: var(--shadow-elevation-2);
        overflow: hidden;
        transition: var(--transition-ease-out);
        transform: translateZ(0);
    }

    .glass-card:hover {
        transform: translateY(-8px) scale(1.005) translateZ(0);
        box-shadow: var(--shadow-elevation-3);
    }

    /* Form Header */
    .form-header {
        background: var(--primary-gradient);
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -70%;
        right: -70%;
        width: 250%;
        height: 250%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 60%);
        transform: rotate(45deg);
        opacity: 0.8;
        animation: headerLight 15s infinite linear;
    }

    @keyframes headerLight {
        0% { transform: rotate(0deg); opacity: 0.8; }
        50% { transform: rotate(360deg); opacity: 1; }
        100% { transform: rotate(0deg); opacity: 0.8; }
    }

    .form-title {
        position: relative;
        z-index: 1;
        font-weight: 700;
        letter-spacing: 0.8px;
        font-size: 1.8rem;
        color: white;
        display: flex;
        align-items: center;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .form-title i {
        margin-right: 1.2rem;
        font-size: 2.2rem;
    }

    /* Form Elements */
    .card-body-custom {
        padding: 2.5rem;
        background-color: var(--card-bg-light); /* Ensure card body is pure white */
    }

    .form-group {
        margin-bottom: 1.75rem;
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 600;
        color: var(--text-color-dark);
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .form-control-modern {
        background: var(--input-bg-light);
        border: 1px solid var(--input-border-light);
        border-radius: 12px;
        padding: 1rem 1.4rem;
        width: 100%;
        color: var(--text-color-form-input);
        font-size: 1rem;
        transition: var(--transition-ease-out);
        appearance: none;
    }

    .form-control-modern::placeholder {
        color: var(--text-color-muted-dark);
        opacity: 0.7;
    }

    .form-control-modern:focus {
        border-color: var(--focus-border-color);
        box-shadow: var(--focus-shadow);
        background: var(--card-bg-light);
        outline: none;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '\f078'; /* Font Awesome chevron-down icon */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 1.4rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-color-muted-dark);
        pointer-events: none;
        font-size: 0.9rem;
        transition: var(--transition-fast);
    }

    /* Balance Display */
    .balance-display {
        background: #EAF0F8; /* Light, distinct background for balance */
        border-radius: 12px;
        padding: 1rem 1.4rem;
        margin-top: 0.5rem;
        margin-bottom: 1.5rem; /* Space before next form group */
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: var(--shadow-elevation-1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .balance-label {
        color: var(--text-color-muted-dark);
    }

    .balance-amount {
        color: var(--primary-accent); /* Use primary accent for balance */
        font-weight: 700;
        font-family: 'Roboto Mono', monospace;
    }

    .text-success-custom {
        color: #4CAF50 !important; /* Specific success green */
    }

    .text-primary-custom {
        color: var(--primary-accent) !important; /* Specific primary accent for balance */
    }

    /* Alert Styling */
    .alert {
        border-radius: 12px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-elevation-1);
    }

    .alert-success {
        background-color: #E6F7ED;
        border-color: #A8DCA8;
        color: #28A745;
    }

    .alert-danger {
        background-color: #FEEBEB;
        border-color: #F5B9B9;
        color: #DC3545;
    }

    .alert i {
        font-size: 1.5rem;
    }

    .alert ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* Submit Button */
    .submit-btn {
        background: var(--primary-gradient); /* Use primary gradient for submit */
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1.2rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.8px;
        transition: var(--transition-ease-out);
        width: 100%;
        box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
        text-transform: uppercase;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: var(--transition-ease-out);
    }

    .submit-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(108, 92, 231, 0.6);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .glass-card {
            border-radius: 16px;
        }
        .form-header {
            padding: 1.8rem;
        }
        .form-title {
            font-size: 1.6rem;
        }
        .form-title i {
            font-size: 2rem;
        }
        .card-body-custom {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        body {
            padding: 1rem;
        }
        .form-header {
            padding: 1.5rem;
        }
        .form-title {
            font-size: 1.4rem;
        }
        .form-title i {
            font-size: 1.8rem;
        }
        .card-body-custom {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            font-size: 0.9rem;
        }
        .form-control-modern {
            padding: 0.8rem 1.2rem;
            font-size: 0.9rem;
        }
        .submit-btn {
            padding: 1rem 1.2rem;
            font-size: 1rem;
        }
        .alert {
            padding: 1rem 1.2rem;
            font-size: 0.9rem;
        }
        .alert i {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .form-header {
            padding: 1.2rem;
        }
        .form-title {
            font-size: 1.1rem;
        }
        .form-title i {
            font-size: 1.5rem;
            margin-right: 0.8rem;
        }
        .card-body-custom {
            padding: 1rem;
        }
    }

        /* Tab Navigation Container */
    .genealogy-tabs-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    
    }
    
    /* Hide scrollbar but keep functionality */
    .genealogy-tabs-container::-webkit-scrollbar {
        display: none;
    }
     .genealogy-tabs {
        display: flex;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 30px -10px rgba(0, 0, 0, 0.1);
        min-width: fit-content;

            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            overflow: hidden;
        }
       .genealogy-tab {
            flex: 1;
            text-align: center;
            padding: 15px 20px;
            color: #718096;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            border-bottom: 3px solid transparent;
        }
           .genealogy-tab:hover {
        color: #4a5568;
        background: var(--primary-gradient);
    }
    
    .genealogy-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: var(--primary-gradient);
    }
    
    .genealogy-tab i {
        margin-right: 8px;
        font-size: 0.9em;
    }
</style>

<div class="page-breadcrumb">

   <div class="container-fluid flex-grow-1">
        <div class="genealogy-tabs-container">
    <div class="genealogy-tabs col-md-4">
        <a href="{{ route('user.walletinternal.index') }}" class="genealogy-tab {{ request()->routeIs('user.walletinternal.index') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-usd"></i> Fund Convert
        </a>
        <a href="{{ route('fund.convert.history') }}" class="genealogy-tab {{ request()->routeIs('fund.convert.history') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> Fund Convert History
        </a>
        
    </div>
        <div class="row g-4 mb-4 "> {{-- Centering the card --}}
            <div class="col-sm-12 col-xl-6">
                <div class="glass-card animate-fade">
                    <div class="form-header">
                        <h3 class="form-title text-white mb-0">
                            <i class="fas fa-exchange-alt me-3"></i> Fund Convert
                        </h3>
                    </div>

                    <div class="card-body-custom">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.walletinternal.transfer') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="from_wallet" class="form-label">
                                    <i class="fas fa-wallet me-2"></i> Select From Wallet
                                </label>
                                <div class="select-wrapper">
                                    <select name="from_wallet" id="from_wallet" class="form-control-modern" required>
                                        <option value="">Select Wallet</option>
                                        @foreach($fromWalletTypes as $wallet)
                                            <option value="{{ $wallet->slug }}" {{ old('from_wallet') == $wallet->slug ? 'selected' : '' }}>
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="balance-display" id="from_wallet_balance" style="display:none;">
                                <span class="balance-label">Balance:</span>
                                <span id="from_wallet_amount" class="balance-amount text-success-custom">{{ company_info('currency', '$') }} 0.00</span>
                            </div>

                            <div class="form-group">
                                <label for="to_wallet" class="form-label">
                                    <i class="fas fa-arrow-right-long me-2"></i> Select To Wallet
                                </label>
                                <div class="select-wrapper">
                                    <select name="to_wallet" id="to_wallet" class="form-control-modern" required>
                                        <option value="">Select Wallet</option>
                                        @foreach($toWalletTypes as $wallet)
                                            <option value="{{ $wallet->slug }}" {{ old('to_wallet') == $wallet->slug ? 'selected' : '' }}>
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="balance-display" id="to_wallet_balance" style="display:none;">
                                <span class="balance-label">Balance:</span>
                                <span id="to_wallet_amount" class="balance-amount text-primary-custom">{{ company_info('currency', '$') }} 0.00</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="amount">
                                    <i class="fas fa-dollar-sign me-2"></i> Enter Amount
                                </label>
                                <input type="number" step="0.01" class="form-control-modern" id="amount" name="amount"
                                       placeholder="0.00" value="{{ old('amount') }}" required />
                            </div>

                            <button type="submit" class="submit-btn mt-3">
                                <i class="fas fa-paper-plane"></i> Transfer Funds
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fromSelect = document.getElementById('from_wallet');
        const toSelect = document.getElementById('to_wallet');

        const fromWalletBalanceDiv = document.getElementById('from_wallet_balance');
        const toWalletBalanceDiv = document.getElementById('to_wallet_balance');
        const fromWalletAmount = document.getElementById('from_wallet_amount');
        const toWalletAmount = document.getElementById('to_wallet_amount');

        // Wallet balances keyed by slug (e.g., 'main_wallet', 'fund_wallet')
        const walletBalances = {
            @foreach(($fromWalletTypes ?? collect())->merge($toWalletTypes ?? collect())->unique('slug') as $wallet)
                "{{ $wallet->slug }}": {{ $adminWallet[$wallet->wallet_column] ?? 0 }},
            @endforeach
        };
        const currencySymbol = "{{ company_info('currency', '$') }}"; // Removed trailing space for cleaner number formatting

        function updateBalance(selectElem, displayDiv, amountSpan) {
            const walletKey = selectElem.value;
            if (walletKey && walletBalances.hasOwnProperty(walletKey)) {
                amountSpan.textContent = currencySymbol + ' ' + walletBalances[walletKey].toFixed(2); // Add space after symbol
                displayDiv.style.display = 'flex'; // Use flex for better alignment of balance display
            } else {
                displayDiv.style.display = 'none';
            }
        }

        function disableSelectedOption(sourceSelect, targetSelect) {
            const selectedValue = sourceSelect.value;
            [...targetSelect.options].forEach(opt => opt.disabled = false); // Re-enable all options first

            if (selectedValue) {
                [...targetSelect.options].forEach(opt => {
                    if (opt.value === selectedValue) opt.disabled = true;
                });

                if (targetSelect.value === selectedValue) {
                    targetSelect.value = ''; // Reset target if selected same
                    updateBalance(targetSelect,
                        targetSelect === fromSelect ? fromWalletBalanceDiv : toWalletBalanceDiv,
                        targetSelect === fromSelect ? fromWalletAmount : toWalletAmount
                    );
                }
            }
        }

        // Initial setup and event listeners
        fromSelect.addEventListener('change', () => {
            disableSelectedOption(fromSelect, toSelect);
            updateBalance(fromSelect, fromWalletBalanceDiv, fromWalletAmount);
        });

        toSelect.addEventListener('change', () => {
            disableSelectedOption(toSelect, fromSelect);
            updateBalance(toSelect, toWalletBalanceDiv, toWalletAmount);
        });

        // Call initially for any pre-selected values (e.g., old('from_wallet'))
        disableSelectedOption(fromSelect, toSelect);
        disableSelectedOption(toSelect, fromSelect); // Ensure initial state reflects restrictions
        updateBalance(fromSelect, fromWalletBalanceDiv, fromWalletAmount);
        updateBalance(toSelect, toWalletBalanceDiv, toWalletAmount);
    });
</script>
@endsection