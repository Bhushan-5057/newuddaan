@extends('superadmin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-title">FUND REQUEST DETAIL</h5>
        <div class="row">
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr><th>User</th><td>: {{ $transaction->user ? $transaction->user->username : 'N/A' }}</td></tr>
                    <tr><th>Amount</th><td>: {{ number_format($transaction->amount, 4) }}</td></tr>
                    <tr><th>Method</th><td>: {{ $transaction->payment_type }}</td></tr>
                    <tr><th>Type</th><td>: {{ $transaction->tx_type }}</td></tr>
                    <tr><th>UTR Number</th><td>: {{ $transaction->tx_hash ?? 'N/A' }}</td></tr>
                    <tr>
                        <th>Payment Slip</th>
                        <td>: 
                            @if ($transaction->payment_slip)
                                <img src="{{ asset('storage/'.$transaction->payment_slip) }}" width="100" />
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr><th>Status</th><td>: 
                        @if ($transaction->status == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif ($transaction->status == 1)
                            <span class="badge bg-success">Approved</span> 
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td></tr>
                    <tr><th>Date</th><td>: {{ $transaction->date }}</td></tr>
                </table>
            </div>
            <div class="col-md-4">
                <form id="actionForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $transaction->id }}">
                   
                     @if ($transaction->status == 0)
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="approveTx({{ $transaction->id }})">Approve</button>
                        <button type="button" class="btn btn-danger" onclick="rejectTx({{ $transaction->id }})">Cancel</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
    @endif

    function approveTx(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this transaction?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('superadmin.transferfundtransactionsrequest.approve') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id
                }, function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: 'Transaction approved.',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Something went wrong.'
                        });
                    }
                });
            }
        });
    }

    function rejectTx(id) {
        Swal.fire({
            title: 'Reject Transaction',
            text: "Please provide a reason for cancellation:",
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Reason',
            inputPlaceholder: 'Type your reason here...',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value.trim()) {
                    return 'Reason is required!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('superadmin.transferfundtransactionsrequest.reject') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    reason: result.value
                }, function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: 'Transaction rejected.',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Something went wrong.'
                        });
                    }
                });
            }
        });
    }
</script>

@endsection
