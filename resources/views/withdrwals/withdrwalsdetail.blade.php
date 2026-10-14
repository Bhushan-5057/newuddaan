@extends('admin.layouts.app')



@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card p-3">

            <h5 class="card-title">Withdrawal Request Detail</h5>

            <div class="row">

                <div class="col-md-8">

                    <table class="table table-borderless">

                        <tr>
                            <th>S No.</th>
                            <td>: {{ $transaction->id }}</td>
                        </tr>

                        <tr>
                            <th>Tx User</th>
                            <td>: {{ $transaction->user ? $transaction->user->username : 'N/A' }}</td>
                        </tr>

                        <!-- <tr><th>User Wallet</th><td>: {{ $transaction->user ? $transaction->user->name : 'N/A' }}</td></tr> -->

                        <tr>
                            <th>Action</th>
                            <td>: {{ $transaction->tx_type }}</td>
                        </tr>

                        <tr>
                            <th>Amount</th>
                            <td>: {{ number_format($transaction->amount, 4) }}</td>
                        </tr>

                        <tr>
                            <th>Tx Charge</th>
                            <td>: {{ number_format($transaction->tx_charge, 4) }}</td>
                        </tr>

                        <tr>
                            <th>Payable Amount</th>
                            <td>: {{ number_format($transaction->paidAmount, 4) }}</td>
                        </tr>

                        <tr>
                            <th>User Wallet Address</th>
                            <td>:
                                <span id="walletAddress">
                                    {{ $transaction->user ? $transaction->user->user_wallet_address : 'N/A' }}
                                </span>

                                @if ($transaction->user && $transaction->user->user_wallet_address)
                                    <button type="button" class="btn btn-xs btn-outline-secondary ms-2"
                                        onclick="copyWalletAddress()">
                                        Copy
                                    </button>
                                @endif
                            </td>
                        </tr>


                        <tr>
                            <th>Status</th>

                            <td>:

                                @if ($transaction->status == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($transaction->status == 1)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif

                            </td>

                        </tr>

                        <tr>
                            <th>Date</th>
                            <td>: {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y h:i A') }}</td>
                        </tr>

                    </table>



                </div>

                <div class="col-md-4">

                    <form id="actionForm">

                        @csrf

                        <input type="hidden" name="id" value="{{ $transaction->id }}">

                        <!--

                        Removed manual reason textarea here because SweetAlert modal will ask for reason on rejection.

                        If you want to keep it, just uncomment this block:



                        <div class="mb-3">

                            <label for="reason">Reason (give reason on cancellation)</label>

                            <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>

                        </div>

                        -->

                        @if ($transaction->status == 0)
                            <div class="d-flex gap-2">

                                <button type="button" class="btn btn-success"
                                    onclick="approveTx({{ $transaction->id }})">Approve</button>

                                <button type="button" class="btn btn-danger"
                                    onclick="rejectTx({{ $transaction->id }})">Cancel</button>

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
        @if (session('success'))

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



        @if ($errors->any())

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

                    $.post("{{ route('admin.withdrwalstransactionsrequest.approve') }}", {

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

                input: 'textarea',

                inputLabel: 'Reason for rejection',

                inputPlaceholder: 'Enter reason...',

                inputAttributes: {

                    'aria-label': 'Enter reason'

                },

                showCancelButton: true,

                confirmButtonText: 'Reject',

                cancelButtonText: 'Cancel',

                preConfirm: (reason) => {

                    if (!reason) {

                        Swal.showValidationMessage('Reason is required');

                    }

                    return reason;

                }

            }).then((result) => {

                if (result.isConfirmed) {

                    $.post("{{ route('admin.withdrwalstransactionsrequest.reject') }}", {

                        _token: "{{ csrf_token() }}",

                        id: id,

                        reason: result.value

                    }, function(response) {

                        if (response.success) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Rejected!',

                                text: 'Transaction has been rejected.',

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

    <script>
function copyWalletAddress() {
    const text = document.getElementById('walletAddress').innerText.trim();

    if (!text || text === 'N/A') {
        alert('Nothing to copy');
        return;
    }

    navigator.clipboard.writeText(text).then(() => {
        alert('Wallet address copied');
    }).catch(() => {
        alert('Copy failed');
    });
}
</script>

@endsection
