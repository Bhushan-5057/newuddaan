@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            Franchise Order #{{ $order->id }}
        </h4>

        <a href="{{ route('admin.franchise.orders') }}"
           class="btn btn-outline-secondary">
            ← Back
        </a>
    </div>

    {{-- ORDER + FRANCHISE INFO --}}
    <div class="row mb-4">

        {{-- FRANCHISE INFO --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">
                    Franchise Information
                </div>
                <div class="card-body">
                    <p><b>Name:</b> {{ $order->franchise->name }}</p>
                    <p><b>Username:</b> {{ $order->franchise->username }}</p>
                    <p><b>Email:</b> {{ $order->franchise->email }}</p>
                    <p><b>Mobile:</b> {{ $order->franchise->mobile }}</p>
                </div>
            </div>
        </div>

        {{-- ORDER INFO --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">
                    Order Information
                </div>
                <div class="card-body">
                    <p><b>Order Amount:</b>  {{ company_info('currency', '$') }} {{ number_format($order->order_amount,2) }}</p>
                    <p><b>Total BV:</b> {{ $order->order_bv }}</p>
                    <p>
                        <b>Status:</b>
                        @if($order->status == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif($order->status == 1)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                    <p>
                        <b>Order Date:</b>
                        {{ \Carbon\Carbon::parse($order->added_on)->format('d M Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- ORDER ITEMS --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Order Items
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>BV</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name ?? 'N/A' }} <br>
                            <small class="text-muted">
                                Code: {{ $item->product->p_code ?? '-' }}
                            </small>
                        </td>
                        <td>{{ $item->qty }}</td>
                        <td> {{ company_info('currency', '$') }} {{ number_format($item->price,2) }}</td>
                        <td>{{ $item->bv }}</td>
                        <td> {{ company_info('currency', '$') }} {{ number_format($item->sub_total,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="5" class="text-end">Total</th>
                        <th> {{ company_info('currency', '$') }} {{ number_format($order->order_amount,2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    @if($order->status == 0)
    <div class="d-flex gap-2">

        <form method="POST"
              action="{{ route('admin.franchise.orders.approve',$order->id) }}">
            @csrf
            <button class="btn btn-success"
                    onclick="return confirm('Approve this order?')">
                ✅ Approve Order
            </button>
        </form>

        <form method="POST"
              action="{{ route('admin.franchise.orders.reject',$order->id) }}">
            @csrf
            <button class="btn btn-danger"
                    onclick="return confirm('Reject this order?')">
                ❌ Reject Order
            </button>
        </form>

    </div>
    @endif

</div>
@endsection