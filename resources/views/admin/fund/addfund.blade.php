@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="col-sm-12 col-xl-6">

            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Fund Transfer</h5>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fund.add') }}" method="POST">
                                @csrf


                                <div class="mb-3">
                                    <label for="wallet_type" class="form-label">Select Wallet</label>
                                    @if(count($wallets) === 1)
                                    <input type="hidden" name="wallet_type" value="{{ $wallets[0]->wallet_column }}">
                                    <p>{{ $wallets[0]->name }}</p> {{-- Optional: Show wallet name as plain text --}}
                                    @else
                                    <select name="wallet_type" id="wallet_type" class="form-control" required>
                                        <option value="">Select Wallet</option>
                                        @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->wallet_column }}">{{ $wallet->name }}</option>
                                        @endforeach
                                    </select>
                                    @endif


                                </div>


                                <div class="mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter username" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="amount">Enter Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="0.00" />
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('scripts')



@endsection