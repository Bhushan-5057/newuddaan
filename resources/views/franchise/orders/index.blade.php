@extends('franchise.layouts.app')
@section('title', 'My Orders')

@section('content')
    <div class="container-fluid py-4 px-lg-5">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">My Orders</h2>
                <p class="text-muted small mb-0">Track and manage your franchise orders</p>
            </div>
            <div>
                <a href="{{ route('franchise.orders.create') }}"
                    class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                    <i class="ki-filled ki-plus fs-4"></i> New Order
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted">Order ID</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Total Amount</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-muted">BV</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Status</th>
                                <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Date</th>
                                <th class="pe-4 py-3 text-end text-uppercase fs-xs fw-bold text-muted">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $order->id }}</td>
                                    <td class="fw-semibold text-dark"> {{ company_info('currency', '$') }}  {{ number_format($order->order_amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-light-primary text-primary fw-bold">{{ $order->order_bv }}</span>
                                    </td>
                                    <td>
                                        @if ($order->status == 0)
                                            <span class="badge bg-light-warning text-warning px-3 py-2">
                                                <i class="fa fa-clock-o me-1"></i> Pending
                                            </span>
                                        @elseif($order->status == 1)
                                            <span class="badge bg-light-success text-success px-3 py-2">
                                                <i class="fa fa-check-circle me-1"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge bg-light-danger text-danger px-3 py-2">
                                                <i class="fa fa-times-circle me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('franchise.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-icon btn-light hover-primary">
                                            <i class="fa fa-eye fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* કસ્ટમ બેજ સ્ટાઇલિંગ */
        .bg-light-warning {
            background-color: #fff8dd !important;
            color: #ffad0f !important;
        }

        .bg-light-success {
            background-color: #e8fff3 !important;
            color: #50cd89 !important;
        }

        .bg-light-danger {
            background-color: #fff5f8 !important;
            color: #f1416c !important;
        }

        .bg-light-primary {
            background-color: #f1faff !important;
            color: #009ef7 !important;
        }

        .fs-xs {
            font-size: 0.75rem;
            letter-spacing: 0.05rem;
        }

        .table thead th {
            border-top: none;
            border-bottom: 1px solid #f1f1f4;
        }

        .hover-primary:hover {
            background-color: #009ef7 !important;
            color: #ffffff !important;
        }
    </style>
@endsection
