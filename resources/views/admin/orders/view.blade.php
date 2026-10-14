@extends('admin.layouts.app')
@section('content')

<div class="container-fluid py-4">
    <div class="card shadow p-4">
        <h4>Order Details</h4>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Order ID:</strong> {{ $order->tx_id }} <br>
                <strong>User:</strong> {{ $order->user?->name ?? 'N/A' }} ({{ $order->user?->u_code ?? 'N/A' }}) <br>
                <strong>Transaction Type:</strong> {{ ucfirst($order->tx_type) }}
            </div>
            <div class="col-md-6">
                <strong>Amount:</strong> ${{ number_format($order->order_amount, 2) }} <br>
                <strong>Status:</strong>
                @if ($order->status == 0)
                    <span class="badge bg-warning">Pending</span>
                @elseif ($order->status == 1)
                    <span class="badge bg-success">Approved</span>
                @elseif ($order->status == 2)
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif <br>
                <strong>Date:</strong> {{ $order->added_on }}
            </div>
        </div>

        <hr>
        <a href="{{ route('admin.topup.history') }}" class="btn btn-primary">Back to Topup History</a>
    </div>
</div>

@endsection
