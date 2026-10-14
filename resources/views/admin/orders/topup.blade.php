@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="col-sm-12 col-xl-6">
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Admin Manual Topup</h5>
                        </div>
                        <div class="card-body">

                            {{-- Success Alert --}}
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Error Alert --}}
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.topup.store') }}" method="POST">
                                @csrf
                                
                                <!-- Username Field -->
                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">Username / User ID:</label>
                                    <input type="text" id="username_input" name="username" class="form-control" placeholder="Enter Username" value="{{ old('username') }}" required>
                                    <small id="user_name_display" class="fw-bold mt-1 d-block"></small>
                                </div>

                                <!-- Order Amount Field -->
                                <div class="form-group mb-3">
                                    <label for="order_amount" class="form-label">Order Amount:</label>
                                    <input type="number" step="0.01" name="order_amount" class="form-control" placeholder="Enter Amount" value="{{ old('order_amount') }}" required>
                                </div>

                                <!-- Order BV Field -->
                                <div class="form-group mb-3">
                                    <label for="order_bv" class="form-label">Order BV:</label>
                                    <input type="number" step="0.01" name="order_bv" class="form-control" placeholder="Enter Order BV" value="{{ old('order_bv') }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Submit Topup</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#username_input').on('keyup blur', function() {
            var username = $(this).val();
            if(username.length > 2) {
                $.ajax({
                    url: "{{ route('admin.check.user') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        username: username
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#user_name_display').removeClass('text-danger').addClass('text-success').text('User: ' + response.name);
                        } else {
                            $('#user_name_display').removeClass('text-success').addClass('text-danger').text('User not found!');
                        }
                    }
                });
            } else {
                $('#user_name_display').text('');
            }
        });
    });
</script>
@endsection