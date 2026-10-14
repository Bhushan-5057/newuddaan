@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Franchise Order #{{ $order->id }}</h4>

        <a href="{{ route('admin.franchise.orders') }}"
           class="btn btn-outline-secondary">
            ← Back
        </a>
    </div>

    {{-- INFO --}}
    <div class="row mb-4">

        {{-- FRANCHISE --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Franchise Information</div>
                <div class="card-body">
                    <p><b>Name:</b> {{ $order->franchise->name }}</p>
                    <p><b>Username:</b> {{ $order->franchise->username }}</p>
                    <p><b>Email:</b> {{ $order->franchise->email }}</p>
                    <p><b>Mobile:</b> {{ $order->franchise->mobile }}</p>
                    <p><b>Wallet Balance:</b>
                        {{ company_info('currency','$') }}
                        {{ number_format($order->franchise->balance,2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ORDER --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Order Information</div>
                <div class="card-body">
                    <p><b>Order Amount:</b>
                        {{ company_info('currency','$') }}
                        {{ number_format($order->order_amount,2) }}
                    </p>

                    <p><b>Total BV:</b> {{ $order->order_bv }}</p>

                    <p>
                        <b>Status:</b>
                        @if($order->status == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif($order->status == 1)
                            <span class="badge bg-success">Approved</span>
                        @elseif($order->status == 2)
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-info">Partial</span>
                        @endif
                    </p>

                    <p><b>Order Date:</b>
                        {{ $order->created_at->format('d M Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS --}}
    <div class="card">
        <div class="card-header fw-bold">Order Items</div>

        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($order->items as $i => $item)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>

                        <td class="text-end">
                            {{ company_info('currency','$') }}
                            {{ number_format($item->sub_total / max($item->quantity,1),2) }}
                        </td>

                        <td class="text-end">
                            {{ company_info('currency','$') }}
                            {{ number_format($item->sub_total,2) }}
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @if($item->status == 0)
                                <span class="badge bg-warning">Pending</span>
                            @elseif($item->status == 1)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td class="text-center">
                            @if($item->status == 0)
                                <div class="d-flex justify-content-center gap-2">
                                    <form method="POST"
                                          action="{{ route('admin.franchise.orders.approve',$item->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve this item?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.franchise.orders.reject',$item->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Reject this item?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Order Total</th>
                        <th class="text-end">
                            {{ company_info('currency','$') }}
                            {{ number_format($order->order_amount,2) }}
                        </th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection