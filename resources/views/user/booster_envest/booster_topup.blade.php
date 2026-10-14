@extends('user.layouts.app')

@section('content')
<style>
    .input-group-text{
        height: 48px !important;
    }
</style>
<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light"> Invest / </span> Booster Topup
        </h4>

        <div class="row g-4">
            <div class="col-sm-12 col-xl-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booster Topup ({{ company_info('currency', '$') }} 10)</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                   <form method="POST" action="{{ route('user.booster.topup.submit') }}">

                            @csrf

                            <div class="mb-3">
                                <label for="wallet" class="form-label">Fund Wallet</label>
                                <select name="wallet" id="wallet" class="form-select" disabled>
                                    <option selected>
                                        Fund Wallet — {{ company_info('currency', '$') }} {{ number_format($userWallet->c2 ?? 0, 2) }}
                                    </option>
                                </select>
                                <input type="hidden" name="wallet" value="c2">
                            </div>
                            
                            <!-- Username Input -->
                            <div class="mb-4">

                            @if ($username_req == 'yes')
                             <label for="username" class="form-label fw-semibold">Enter Username</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" id="username" class="form-control" required autocomplete="off" placeholder="Enter username">
                                </div>
                            @else
                                    <input type="hidden" name="username" id="username" class="form-control" value="{{ Auth::user()->username }}">
                            @endif


                               
                                <div id="user-name" class="mt-2 fw-medium"></div>
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Booster Package</label>
                                <input type="text" class="form-control" value="{{ company_info('currency', '$') }} 10 Fixed" disabled>
                            </div>

                            {{--<button type="submit" class="btn btn-warning px-4">Buy Booster Package</button>--}}
                            
                            @if ($canBuy)
                      {{-- Buy enable --}}
                                <button type="submit" class="btn btn-warning px-4">
                                    Buy Booster Package
                                </button>
                            @else
                                {{-- Buy disable look, but clickable for popup --}}
                                <button type="button" class="btn btn-warning px-4" onclick="buyErrorPopup()">
                                    Buy Booster Package
                                </button>
                            @endif

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-4">
    <div class="card bg-gradient-primary text-white shadow-lg">
        <div class="card-body"> 
            <div class="feature-list">
                <div class="feature-item mb-3">
                    <div class="feature-icon bg-white text-primary rounded-circle">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="feature-text">
                        <strong>$10 Booster Package</strong>
                        <p class="mb-0">Separate topup required for booster program</p>
                    </div>
                </div>

                <div class="feature-item mb-3">
                    <div class="feature-icon bg-white text-primary rounded-circle">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Global 2×2 Matrix</strong>
                        <p class="mb-0">Earn 100% income from 4 users (2 levels deep)</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon bg-white text-primary rounded-circle">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Multiple Packages</strong>
                        <p class="mb-0">Purchase unlimited booster packages for more income</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .feature-list {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        padding: 12px;
        border-radius: 8px;
        background-color: rgba(255,255,255,0.05);
        margin-bottom: 10px;
    }
    
    .feature-item:last-child {
        margin-bottom: 0;
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 15px;
        font-size: 1rem;
    }
    
    .feature-text {
        flex-grow: 1;
    }
    
    .feature-text strong {
        display: block;
        margin-bottom: 3px;
    }
    
    .feature-text p {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
</style>
        </div>
    </div>
</div>



@endsection
@section('scripts')
<script>
function buyErrorPopup() {
    Swal.fire({
        icon: 'error',
        title: 'Cannot Buy Package',
        text: 'You already have an active booster package.',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}
</script>
@endsection
