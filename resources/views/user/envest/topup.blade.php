@extends('user.layouts.app')

@section('content')
<style>


    /* Main Container */
    .topup-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Section */
    .topup-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .topup-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
       background: var(--primary-gradient);

        border-radius: 50%;
    }

    .topup-header h2 {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .topup-header p {
        opacity: 0.9;
        font-size: 1rem;
        max-width: 600px;
        position: relative;
        z-index: 1;
    }

    /* Navigation Tabs */
    .topup-tabs {
        display: flex;
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .topup-tab {
        flex: 1;
        text-align: center;
        padding: 18px 20px;
        color: var(--dark-color) !important;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        position: relative;
        border-bottom: 3px solid transparent;
    }

    .topup-tab:hover {
         color: var(--primary-color) !important;
            background: rgb(108 92 231 / 14%);
    }

    .topup-tab.active {
        color: var(--primary-color) !important;
        border-bottom-color: var(--primary-color);
         background: rgb(108 92 231 / 14%);
    }

    .topup-tab i {
        margin-right: 8px;
        font-size: 1.1em;
    }

    /* Form Card */
    .form-card {
        background: #ffffff14;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 30px;
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.1);
    }

    .form-card-header {
        padding: 20px 30px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
       background: var(--primary-gradient);

    }

    .form-card-header h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
    }

    .form-card-header h3 i {
        margin-right: 12px;
        color: var(--primary-color);
    }

    .form-card-body {
        padding: 30px;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--light-color);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 8px;
        color: var(--primary-color);
    }

    .form-control, .form-select {
        border-radius: 10px;
        /* padding: 14px 18px; */
        border: 1px solid #e0e0e0;
        transition: var(--transition);
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e0e0e0;
    }

    /* Button Styles */
    .btn-topup {
          background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(15, 189, 29, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-topup:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(108, 92, 231, 0.4);
        color: white;
    }

    .btn-topup i {
        margin-right: 10px;
    }

    /* Info Card */
    .info-card {
        background: #fff;

        color: #000;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .info-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .info-card h3 {
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
    }

    .info-card h3 i {
        margin-right: 12px;
    }

    .steps-list {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .step-number {
          background: var(--primary-gradient);
        color: #fff;
        height: 28px;
            width: 23px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .step-content {
        color: rgba(15, 14, 14, 0.9);
    }

    /* Help Card */
    .help-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 30px;
        transition: var(--transition);
    }

    .help-card:hover {
        transform: translateY(-5px);
    }

    .help-card h3 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .help-card h3 i {
        margin-right: 12px;
        color: var(--info-color);
    }

    .help-card p {
        color: #666;
        margin-bottom: 20px;
    }

    .btn-help {
        background: var(--info-color);
        color: white;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-help:hover {
        background: #0a7bd4;
        color: white;
        transform: translateY(-2px);
    }

    /* User Validation Feedback */
    .user-validation {
        padding: 10px;
        border-radius: 8px;
        margin-top: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .user-validation i {
        margin-right: 8px;
    }

    .valid-user {
        background: rgba(0, 184, 148, 0.1);
        color: var(--success-color);
    }

    .invalid-user {
        background: rgba(214, 48, 49, 0.1);
        color: var(--danger-color);
    }

    .checking-user {
        background: rgba(9, 132, 227, 0.1);
        color: var(--info-color);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .topup-header {
            padding: 25px;
        }
        
        .form-card-body, .help-card {
            padding: 25px;
        }
    }

    @media (max-width: 768px) {
        .topup-tabs {
            flex-direction: column;
        }
        
        .topup-tab {
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-left: 3px solid transparent;
        }
        
        .topup-tab.active {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-left: 3px solid var(--primary-color);
        }
        
        .form-card-header {
            padding: 15px 20px;
        }
    }

    @media (max-width: 576px) {
        .topup-header {
            padding: 20px;
            text-align: center;
        }
        
        .topup-header h2 {
            font-size: 1.8rem;
        }
        
        .form-card-body, .help-card {
            padding: 20px;
        }
        
        .btn-topup {
            width: 100%;
        }
    }

    /* Animation Effects */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-card {
        animation: fadeInUp 0.6s ease forwards;
    }

    .form-card {
        animation-delay: 0.1s;
    }

    .info-card {
        animation-delay: 0.2s;
    }

    .help-card {
        animation-delay: 0.3s;
    }
</style>

<div class="topup-container">

  

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-8 mb-4">
            <div class="form-card animate-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-wallet"></i> Topup Form</h3>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('user.topup.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="topupType" value="{{ $topupType }}">

                        <!-- Wallet Selection -->
                        <div class="mb-4">
                            <label for="wallet" class="form-label">
                                <i class="fas fa-piggy-bank"></i> Select Wallet
                            </label>
                            <select name="wallet" id="wallet" class="form-select" required>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->wallet_column }}">
                                        {{ $wallet->name }} - {{  company_info('currency_bv', '$') }} {{ number_format($userWallet->{$wallet->wallet_column} ?? 0, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a wallet</div>
                        </div>

                        <!-- Username Input -->
                        <div class="mb-4">
                            @if ($username_req == 'yes')
                                <label for="username" class="form-label">
                                    <i class="fas fa-user-tag"></i> Recipient Username
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="text" name="username" id="username" class="form-control" value="{{ Auth::user()->username }}" required autocomplete="off" placeholder="Enter recipient username">
                                </div>
                                <div id="user-name" class="mt-2"></div>
                            @else
                                <input type="hidden" name="username" id="username" class="form-control" value="{{ Auth::user()->username }}">
                            @endif
                            @error('username')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Package Selection (for pin/pin amount) -->
                        @if($topupType === 'pin' || $topupType === 'pin amount')
                            <div class="mb-4">
                                <label for="package" class="form-label">
                                    <i class="fas fa-box-open"></i> Select Package
                                </label>
                                <select id="package" name="package" class="form-select" required>
                                    @foreach($packages as $package)
                                        @if($topupType === 'pin')
                                            <option value="{{ $package['id'] }}">{{  company_info('currency_bv', '$') }} {{ $package['min'] }}</option>
                                        @else
                                            <option value="{{ $package['id'] }}" data-min="{{ $package['min'] }}" data-max="{{ $package['max'] }}">
                                            {{ $package['name'] }} - {{  company_info('currency_bv', '$') }}{{ $package['min'] }} To {{ $package['max'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Amount Input (for amount/pin amount) -->
                        @if($topupType === 'amount' || $topupType === 'pin amount')
                            <div class="mb-4">
                                <label for="packageAmount" class="form-label">
                                    <i class="fas fa-money-bill-wave"></i> Amount
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{  company_info('currency_bv', '$') }}</span>
                                    <input type="number" id="packageAmount" name="packageAmount"
                                           class="form-control" 
                                           @if($topupType === 'amount')
                                               min="{{ $packages[0]['min'] }}" max="{{ $packages[0]['max'] }}"
                                           @else
                                               min="{{ $packages[0]['min'] }}" max="{{ $packages[0]['max'] }}"
                                           @endif
                                           required
                                           placeholder="Enter amount">
                                </div>
                                <div class="text-danger small mt-1 d-none" id="amountError">
                                    @if($topupType === 'amount')
                                        Amount must be between {{  company_info('currency_bv', '$') }} {{ $packages[0]['min'] }} and {{  company_info('currency_bv', '$') }} {{ $packages[0]['max'] }}
                                    @else
                                        Please enter a valid amount within the selected package range
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-topup">
                                <i class="fas fa-paper-plane"></i> Process Topup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Steps Card -->
            <div class="info-card animate-card">
                <h3><i class="fas fa-list-ol"></i> How To Topup</h3>
                <ul class="steps-list">
                    <li class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">Select wallet you want to transfer from</div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">Enter recipient username (or your own for self-topup)</div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">Choose package or enter amount</div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">Confirm and complete the transaction</div>
                    </li>
                </ul>
            </div>

          
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Amount validation logic
    const type = @json($topupType);
    const input = document.getElementById('packageAmount');
    const error = document.getElementById('amountError');

    if (input && error) {
        input.addEventListener('input', function () {
            let value = parseFloat(input.value);
            let min = parseFloat(input.min || 0);
            let max = parseFloat(input.max || Infinity);

            if (type === 'pin amount') {
                const selectedOption = document.querySelector('#package option:checked');
                min = parseFloat(selectedOption.getAttribute('data-min'));
                max = parseFloat(selectedOption.getAttribute('data-max'));
            }

            if (value < min || value > max || isNaN(value)) {
                error.classList.remove('d-none');
                input.classList.add('is-invalid');
            } else {
                error.classList.add('d-none');
                input.classList.remove('is-invalid');
            }
        });

        if (type === 'pin amount') {
            document.getElementById('package').addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                input.min = selected.getAttribute('data-min');
                input.max = selected.getAttribute('data-max');
                input.dispatchEvent(new Event('input'));
            });
        }
    }
});

// Username lookup with debounce
$(document).ready(function() {
    let timer;
    $('#username').on('input', function () {
        clearTimeout(timer);
        let username = $(this).val();

        if (username.length > 2) {
            $('#user-name').html('<div class="user-validation checking-user"><i class="fas fa-spinner fa-spin"></i> Validating username...</div>');
            
            timer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('user.getUserName') }}",
                    type: "GET",
                    data: { username: username },
                    success: function (response) {
                        if (response.success) {
                            $('#user-name').html(`<div class="user-validation valid-user"><i class="fas fa-check-circle"></i> Valid user: ${response.name}</div>`);
                        } else {
                            $('#user-name').html('<div class="user-validation invalid-user"><i class="fas fa-times-circle"></i> User not found</div>');
                        }
                    },
                    error: function () {
                        $('#user-name').html('<div class="user-validation invalid-user"><i class="fas fa-exclamation-triangle"></i> Error checking username</div>');
                    }
                });
            }, 500); // 0.5 second delay after typing stops
        } else if (username.length > 0) {
            $('#user-name').html('<div class="user-validation invalid-user"><i class="fas fa-info-circle"></i> Enter at least 3 characters</div>');
        } else {
            $('#user-name').html('');
        }
    });
});
</script>
@endsection