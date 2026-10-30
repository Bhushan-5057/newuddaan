@extends('user.layouts.app')

@section('content')

<div class="container">
    <h4 class="mb-4">My Product Orders</h4>

    @forelse($orders as $order)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <b>Order ID :</b> {{ $order->tx_id }}
                    <br>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($order->added_on)->format('d M Y h:i A') }}
                    </small>
                </div>

                <div>
                    @if($order->status == 2)
                        <span class="badge bg-success">Delivered</span>
                    @elseif($order->status == 1)
                        <span class="badge bg-info">Dispatch</span>
                    @elseif($order->status == 0)
                        <span class="badge bg-warning">Pending</span>
                    @elseif($order->status == 3)
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </div>

                <div>
                    <a href="{{ route('order.invoice', $order->tx_id) }}"
                       class="btn btn-sm btn-success">
                        <i class="bx bx-printer me-1"></i> Invoice
                    </a>
                </div>
            </div>

            <div class="card-body">
                @forelse($order->items as $item)
                    <div class="row align-items-center mb-3">
                        <div class="col-md-2">
                            @if($item->product)
                                <img src="{{ asset('storage/'.$item->product->imgs) }}"
                                     style="width:80px;border-radius:6px"
                                     alt="{{ $item->product->name ?? 'Product' }}">
                            @else
                                <div style="width:80px;height:80px;border-radius:6px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-size:12px;color:#999;">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($item->product)
                                <h6>{{ $item->product->name }}</h6>
                            @else
                                <h6 class="text-danger">Product Unavailable</h6>
                                <small class="text-muted">Product ID: #{{ $item->product_id }}</small>
                            @endif
                            <small>Qty : {{ $item->qty }}</small>
                        </div>

                        <div class="col-md-3">
                            <b>BV :</b>
                            {{ number_format($item->bv, 2) }}
                        </div>

                        <div class="col-md-3">
                            <b>Price :</b>
                            {{ company_info('currency','₹') }}
                            {{ number_format($item->price, 2) }}
                        </div>
                    </div>
                    <hr>
                @empty
                    <div class="text-center text-muted py-3">
                        No items found for this order.
                    </div>
                @endforelse

                <div class="text-end">
                    <b>Total :</b>
                    {{ company_info('currency_bv','₹') }}
                    {{ number_format($order->order_amount, 2) }}
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-shopping-bag" style="font-size:48px;color:#ddd;"></i>
                <h5 class="mt-3">No Orders Found</h5>
                <p class="text-muted">You haven't placed any product orders yet.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        </div>
    @endforelse
</div>

@endsection