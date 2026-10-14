@extends('user.layouts.app')



@section('content')

<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">

    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="py-3 breadcrumb-wrapper mb-4">

            <span class="text-muted fw-light"> Withdrawal / </span> Principal Withdrawal

        </h4>



        <div class="row g-4">

            <div class="col-sm-12 col-xl-8">

                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">Principal Withdrawal</h5>

                    </div>

                    <div class="card-body">

                        @if(session('success'))

                            <div class="alert alert-success">{{ session('success') }}</div>

                        @endif



                        @if($errors->any())

                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                       <form method="POST" action="{{ route('user.principal.withdraw.store') }}">
    @csrf

    <div class="mb-3">
        <label for="order_tx_id" class="form-label">Select Order to Withdraw</label>
        <select name="order_tx_id" id="order_tx_id" class="form-select" required>
            @foreach($orders as $order)
                <option value="{{ $order->id }}">
                    {{ $order->tx_id }} -  {{ company_info('currency', '$') . ' ' . number_format($order->order_amount, 2) }} ({{ $order->added_on->format('d M Y') }})
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-warning px-4">Withdraw</button>
</form>


                    </div>

                </div>

            </div>



            <div class="col-sm-12 col-xl-4">

                <div class="card bg-warning text-white">
    <div class="card-header">
        <h5 class="mb-0">STEPS FOR PRINCIPAL WITHDRAWAL</h5>
    </div>

  <div class="card-body">
    <ul class="mb-0 list-unstyled">
        <li class="mb-2">✔ If the order is withdrawn in the <strong>1st month</strong>, a <strong>30% charge</strong> will be applied</li>
        <li class="mb-2">✔ If the order is withdrawn in the <strong>2nd month</strong>, a <strong>20% charge</strong> will be applied</li>
        <li class="mb-2">✔ If the order is withdrawn in the <strong>3rd month</strong>, a <strong>10% charge</strong> will be applied</li>
        <li class="mb-2">✔ After the <strong>3rd month</strong>, <strong>no charges</strong> will be applied (100% amount will be given)</li>
        <li class="mb-0">✔ Click the "Withdraw" button to submit your request</li>
    </ul>
</div>

</div>


            </div>

        </div>

    </div>

</div>



@endsection



@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





<script>

    $('#username').on('keyup', function () {

        let username = $(this).val();



        if (username.length > 2) { // start after 3 characters

            $.ajax({

                url: "{{ route('user.getUserName') }}",

                type: "GET",

                data: { username: username },

                success: function (response) {

                    if (response.success) {

                        $('#user-name').html(`<span class="text-success">Name: ${response.name}</span>`);

                    } else {

                        $('#user-name').html('<span class="text-danger">User not found</span>');

                    }

                },

                error: function () {

                    $('#user-name').html('<span class="text-danger">Error checking username</span>');

                }

            });

        } else {

            $('#user-name').html('');

        }

    });

</script>

@endsection

