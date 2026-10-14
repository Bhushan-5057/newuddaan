@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card p-3">
            <h5 class="card-title">FUND REQUEST DETAIL</h5>
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th>User</th>
                            <td>: {{ $transaction->user ? $transaction->user->username : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>: {{ number_format($transaction->amount, 4) }}</td>
                        </tr>
                        <tr>
                            <th>Method</th>
                            <td>: {{ $transaction->payment_type }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>: {{ $transaction->tx_type }}</td>
                        </tr>
                        <tr>
                            <th>Transaction Hash</th>
                            <td>:
                                <span id="txHash">{{ $transaction->tx_hash ?? 'N/A' }}</span>

                                @if ($transaction->tx_hash)
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                        onclick="copyText('txHash')">
                                        Copy
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Wallet Address</th>
                            <td>:
                                <span id="walletAddress">{{ $transaction->cripto_address ?? 'N/A' }}</span>

                                @if ($transaction->cripto_address)
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                        onclick="copyText('walletAddress')">
                                        Copy
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Payment Slip</th>
                            <td>:
                                @if ($transaction->payment_slip)
                                    <img src="{{ asset('storage/' . $transaction->payment_slip) }}" width="100" />
                                @else
                                    N/A
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
                            <td>: {{ $transaction->date }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <form id="actionForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $transaction->id }}">
                        <div class="mb-3">
                            <label for="reason">Reason (give reason on cancellation)</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success"
                                onclick="approveTx({{ $transaction->id }})">Approve</button>
                            <button type="button" class="btn btn-danger"
                                onclick="rejectTx({{ $transaction->id }})">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script>
function copyText(elementId) {
    const text = document.getElementById(elementId).innerText;

    if (!text || text === 'N/A') {
        alert('Nothing to copy');
        return;
    }

    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard');
    }).catch(() => {
        alert('Copy failed');
    });
}
</script>

    <script>
        function approveTx(id) {
            if (confirm('Are you sure you want to approve this transaction?')) {
                $.post("{{ route('admin.transferfundtransactionsrequest.approve') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id
                }, function(response) {
                    if (response.success) {
                        alert('Transaction approved.');
                        location.reload();
                    } else {
                        alert(response.message || 'Something went wrong.');
                    }
                });
            }
        }

        function rejectTx(id) {
            let reason = $('#reason').val().trim();
            if (!reason) {
                alert('Please provide a reason for cancellation.');
                return;
            }
            if (confirm('Are you sure you want to reject this transaction?')) {
                $.post("{{ route('admin.transferfundtransactionsrequest.reject') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    reason: reason
                }, function(response) {
                    if (response.success) {
                        alert('Transaction rejected.');
                        location.reload();
                    } else {
                        alert(response.message || 'Something went wrong.');
                    }
                });
            }
        }
    </script>
@endsection
