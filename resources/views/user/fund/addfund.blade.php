@extends('user.layouts.app')

@section('content')

<style>
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .page-title-box {
        padding: 1.5rem 0;
    }
    .card-header {
        border-bottom: none;
    }
    .form-control-lg {
        height: calc(3.5rem + 2px);
        font-size: 1.25rem;
    }
    .hidden{
        display: none !important;
    }

</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 " style="color:white !important;">Transfer Funds</h5>
                </div>
                <div class="card-body">
                    <!--<form action="" method="POST">-->
                        @csrf
                        @php 
                        $tokenRate = company_info('token_rate', '0.5');
                        @endphp    
                        <div class="mb-4">
                            <label for="amount" class="form-label">Enter Amount In USDT</label>
                            <div class="input-group">
                                <!--<span class="input-group-text">$</span>-->
                                <input type="number" 
                                       class="form-control form-control-lg" 
                                       id="amount" 
                                       name="amount" 
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0">
                                <input type="hidden" value="" id="tokenAmount">
                            </div>
                            <!--<small class="text-muted">Minimum transfer amount: $10.00</small>-->
                        </div>
                         <div id="coinInfo" class="mt-1 text-info small"></div>
                        <input type="hidden" class="form-control" id="usdtRecipient" value="{{ company_info('company_smart_chain_address', '') }}" readonly>
                        <input type="hidden" id="AccountRecipient" name="AccountRecipient" value="">    
                        {{-- <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Send Transfer
                            </button>
                        </div> --}} 
                         <!-- Wallet Address -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-wallet2 me-1 text-primary"></i>Wallet Address:
                            </label>
                            <div id="walletAddress" class="alert alert-secondary py-2 px-3 mb-0 small">Not connected</div>
                        </div>

                        <!-- USDT Balance -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-piggy-bank me-1 text-warning"></i>USDT Balance:
                            </label>
                            <div id="maticBalance" class="d-none">0</div>
                            <div id="usdtBalance" class="badge bg-info text-dark p-2">0</div>
                        </div>

                        <!-- Status Message -->
                        <p id="statusMessage" class="mt-3 text-white small"></p>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-success flex-fill hidden" id="sendUsdt">
                                <i class="bi bi-send-check-fill me-1"></i>Transfer USDT
                            </button>
                            <button class="btn btn-primary flex-fill" id="connectButton">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Connect Wallet
                            </button>
                            <button class="btn btn-danger flex-fill hidden" id="Disconnect">
                                <i class="bi bi-box-arrow-left me-1"></i>Disconnect
                            </button>
                        </div>
                    <!--</form>-->
                </div>
                
                {{--<div class="card-footer bg-light">
                    <div class="text-center">
                        <small class="text-muted">Transactions may take 1-3 business days to process</small>
                    </div>
                </div>--}}
            </div>
            
        </div>
    </div>
</div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
/* Modal Layer */
.modal-layer {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.5); /* lighter overlay */
    backdrop-filter: blur(3px);
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.modal-layer.show {
    display: flex;
    opacity: 1;
}

/* Modal Card */
.modal-card {
    width: min(420px, 90vw);
    background: #fff; /* lighter background for visibility */
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    padding: 30px 20px;
    text-align: center;
    color: #333;
    position: relative;
}

/* Loader */
.loader-wrap {
    width: 80px;
    height: 80px;
    margin: 0 auto 16px;
    position: relative;
}
.ring1, .ring2 {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 5px solid rgba(0,0,0,0.1);
    border-top-color: #0d6efd;
    animation: spin 1.2s linear infinite;
}
.ring2 {
    inset: 12px;
    border-width: 3px;
    border-top-color: #198754;
    animation-direction: reverse;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success Checkmark */
.success-animation {
    margin-bottom: 15px;
}
.success-mark {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    position: relative;
}
.success-mark::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #198754;
}
.success-stem, .success-kick {
    position: absolute;
    background: #fff;
    border-radius: 3px;
    opacity: 0;
    transform-origin: left top;
}
.success-stem { width: 8px; height: 40px; left: 55px; top: 24px; transform: rotate(45deg); animation: stem .35s .35s forwards; }
.success-kick { width: 8px; height: 20px; left: 16px; top: 42px; transform: rotate(-45deg); animation: kick .35s .55s forwards; }
@keyframes stem { from { height: 0; opacity: 1; } to { height: 40px; opacity: 1; } }
@keyframes kick { from { width: 0; opacity: 1; } to { width: 8px; opacity: 1; } }

/* Buttons in Modal */
.modal-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
}
.modal-actions .modal-btn {
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
}

/* Loading & Success Text */
#loadingText, .success-text {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}
#loadingText + p, .success-message {
    font-size: 13px;
    color: #555;
}

/* Ensure modal is visible on top */
.modal-layer, .modal-card {
    z-index: 1050;
}
</style>

   <!-- Demo button to show modals -->
    <!-- <button class="demo-btn" id="demoTrigger">Show Transaction Flow</button> -->

    <!-- Loading Modal -->
  <div id="loadingModal" class="modal-layer" role="dialog" aria-modal="true" aria-labelledby="loadingText">
    <div class="modal-card">
      <div class="loader-wrap">
        <div class="ring1"></div>
        <div class="ring2"></div>
      </div>
      <p id="loadingText" style="margin:0 0 6px;color:var(--text);font-weight:600">Please wait for confirmation</p>
      <p style="margin:0;color:var(--muted);font-size:13px">Signing and broadcasting your transaction</p>
    </div>
  </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal-layer" role="dialog" aria-modal="true" aria-labelledby="successText">
        <div class="modal-backdrop"></div>
        <div class="modal-card">
            <div class="success-animation">
                <div class="success-circle"></div>
                <div class="success-checkmark">
                    <svg width="50" height="50" viewBox="0 0 50 50">
                        <path d="M18,25 L22,29 L32,19" fill="none"/>
                    </svg>
                </div>
            </div>
            <h3 id="successText" class="text-black">Transfer Successful!</h3>
            <p id="successMessage" class="success-message">Your transaction has been confirmed on the blockchain.</p>
            <div class="modal-actions">
                <a href="{{ route('user.addfund.history') }}" class="modal-btn btn-primary text-muted">Close</a>
                <!-- <a href="#" class="modal-btn btn-outline">New Transaction</a> -->
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
// ===== Modals =====
    const loadingModal = document.getElementById('loadingModal');
    function showLoadingModal(){ loadingModal.classList.add('show'); }
    function hideLoadingModal(){ loadingModal.classList.remove('show'); }

    function showSuccessModal(txHash, receivedAmount){
        const modal = document.getElementById('successModal');
        const amountText = document.getElementById('receivedAmount');
        
        // Update the received amount if element exists
        if(amountText){
            amountText.textContent = receivedAmount;
        }
    
        // Hide loading modal if visible
        hideLoadingModal();
    
        // Show success modal
        modal.classList.add('show');
    }
        
    const csrfToken = "{{ csrf_token() }}";
    const panelPath = "{{ company_info('base_url') ?? 'https://coinicon.io/' }}";
    const panelFUNDPath = "{{ route('user.fund.dapp') }}";
    const coinInfo = document.getElementById('coinInfo');
    const tokenRate = {{ $tokenRate }};
    const input = document.getElementById('amount');
    const tokeninput = document.getElementById('tokenAmount');
    if (input && coinInfo) {
        function updateInfo() {
            const value = parseFloat(input.value) || 0;
    
            // Show coin calculation
            if (value > 0) {
                const coins = (value * tokenRate).toFixed(0);
                tokeninput.value = coins;
                //coinInfo.textContent = ` Live Rate Converted in USDT ${coins}.`;
                //coinInfo.classList.remove('d-none');
            } else {
                coinInfo.classList.add('d-none');
            }
        }
    
        // Attach listeners
        input.addEventListener('input', updateInfo);
        //walletSelect.addEventListener('change', updateInfo);
    
        // Trigger once on page load
        window.addEventListener('DOMContentLoaded', () => {
            //updateInfo();   // just call directly
            updateInfo();
            // OR simulate user typing:
             input.dispatchEvent(new Event('input'));
        });
    }
</script>
<script src="{{ asset('assets/wallet/web3.min.js') }}"
        integrity="sha512-YD0p/mrfISfNjG4WS/8Dx1h6hZx0/IgCFJfQR8lSXf/y29Jy+JcZj1s65/Ar22xBwvK8xe1u1BQ/w2E2Edr22Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module" src="{{ asset('assets/wallet/app.js?v=0.00004') }}"></script>
@endsection