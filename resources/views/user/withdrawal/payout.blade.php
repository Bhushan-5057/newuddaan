@extends('user.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    .mdi:before, .mdi-set {
        color: #0f626a;
    }
</style>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="nav nav-pills gap-2">
                <a href="{{ route('user.payout.index') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.payout.index') ? 'active bg-primary ' : 'border text-secondary' }}">
                    <i class="fas fa-users me-2"></i> Withdrawal
                </a>
                <a href="{{ route('user.withdrawals') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.withdrawals') ? 'active bg-primary ' : 'border text-secondary' }}">
                    <i class="fas fa-user-friends me-2"></i> Withdrawal Report
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @if($kycBank)
            <div class="col-12 mb-4">
                <div class="alert alert-success d-flex align-items-center justify-content-between shadow-sm p-3 mb-0">
                    <div>
                        <strong class="d-block mb-1"><i class="fas fa-university me-2"></i> Bank Verified:</strong>
                        <span class="small">
                            {{ $kycBank->bank_name }} | 
                            {{ $kycBank->account_holder_name }} | 
                            {{ substr($kycBank->account_no, 0, 2) }}XXXX{{ substr($kycBank->account_no, -2) }} | 
                            IFSC: {{ $kycBank->ifsc_code }}
                        </span>
                    </div>
                    <span class="badge bg-success px-3 py-2 text-uppercase">Approved</span>
                </div>
            </div>
        @else
            <div class="col-12 mb-4">
                <div class="alert alert-warning d-flex align-items-center justify-content-between shadow-sm p-3 mb-0">
                    <div>
                        <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle me-2"></i> Bank KYC Pending</strong>
                        <span class="small">Please complete your Bank KYC parameters to unlock instant system withdrawals.</span>
                    </div>
                    <a href="{{ route('user.kyc.show') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                        Complete KYC
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="card-title h5 mb-4 border-bottom pb-2 text-primary">
                        <i class="mdi mdi-bank-transfer-out me-2"></i> Request Withdraw
                    </h3>
                    
                    <form method="POST" action="{{ route('user.payout.store') }}">
                        @csrf
                        
                        @if(count($wallets) === 1)
                            @php
                                $wallet = $wallets->first();
                                $balance = $userWallet->{$wallet->wallet_column} ?? 0;
                            @endphp
                            <div class="border rounded p-3 mb-4 d-flex align-items-center shadow-sm">
                                <div class="bg-primary  rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-wallet fs-5"></i>
                                </div>
                                <div>
                                    <span class="text-secondary small d-block mb-1">{{ $wallet->name }} Balance</span>
                                    <strong class="fs-4 text-dark">{{ company_info('currency', '$') }} {{ number_format($balance, 2) }}</strong>
                                </div>
                            </div>
                            <input type="hidden" name="wallet_slug" value="{{ $wallet->slug }}">
                        @elseif(count($wallets) > 1)
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Select Wallet Source</label>
                                <select class="form-select py-2" name="wallet_slug" required>
                                    <option value="">-- Choose Payout Wallet --</option>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->slug }}">
                                            {{ $wallet->name }} (Balance: ${{ number_format($userWallet->{$wallet->wallet_column} ?? 0, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="alert alert-danger p-3 mb-4">
                                <i class="fas fa-times-circle me-2"></i> No active withdrawal wallets are configured.
                            </div>
                        @endif

                        <div class="mb-4 position-relative">
                            <label for="amount" class="form-label fw-bold text-secondary">Enter Withdrawal Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-currency-usd"></i></span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       class="form-control py-2" 
                                       placeholder="e.g. {{ number_format($minWithdrawal, 2) }}" 
                                       required 
                                       min="{{ $minWithdrawal }}" 
                                       max="{{ $maxLimit }}"
                                       step="0.01">
                            </div>
                            <small class="text-muted d-block mt-1">
                                You can withdraw up to your available My Wallet balance:
                                {{ company_info('currency', '$') }} {{ number_format($maxLimit, 2) }}.
                            </small>
                            
                            @error('amount')
                                <div class="text-danger small mt-2 d-flex align-items-center">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" id="submitBtn">
                            <i class="mdi mdi-send me-2"></i> Process Request Withdrawal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="card-title h5 mb-4 border-bottom pb-2 text-secondary">
                        <i class="mdi mdi-information-outline me-2"></i> Withdrawal Specifications
                    </h3>

                    <div class="mb-4">
                        <span class="text-muted small d-block mb-1">Minimum Allowable Amount</span>
                        <strong class="fs-3 text-success">
                            {{ company_info('currency', '$') }} {{ number_format($minWithdrawal, 2) }}
                        </strong>
                    </div>

                    <div class="border rounded p-3">
                        <strong class="text-danger d-block mb-2 small text-uppercase fw-bold">
                            <i class="mdi mdi-alert-circle-outline me-1"></i> Critical Framework Notes
                        </strong>
                        <ul class="list-unstyled mb-0 small text-secondary d-flex flex-column gap-2.5">
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span>Minimum withdrawal baseline configured is {{ company_info('currency', '$') }} {{ number_format($minWithdrawal, 2) }}.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span>Maximum withdrawable per request is your available My Wallet balance: {{ company_info('currency', '$') }} {{ number_format($maxLimit, 2) }}.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span>A platform operational surcharge fee of <strong>{{ $chargeAmount }}%</strong> will execute on processing.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span>Standard ledger batch settlements process cycles require 1-3 working business days.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span>Authorized active nodes can securely request balances out from Income storage wallets anytime.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const submitBtn = document.getElementById('submitBtn');
        const minWithdrawal = parseFloat('{{ $minWithdrawal }}');

        if (amountInput && submitBtn) {
            submitBtn.disabled = true; // Safe lock validation baseline
            
            amountInput.addEventListener('input', function() {
                let val = parseFloat(this.value);

                if (!isNaN(val) && val >= minWithdrawal) {
                    this.setCustomValidity('');
                    submitBtn.disabled = false;
                } else {
                    this.setCustomValidity('Amount must be at least ${{ number_format($minWithdrawal, 2) }}.');
                    submitBtn.disabled = true;
                }
            });
        }
    });
</script>
@endsection