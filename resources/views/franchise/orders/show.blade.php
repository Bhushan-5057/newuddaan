@extends('franchise.layouts.app')
@section('title','Order Details')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Order #{{ $order->id }}</h2>
            <p class="text-muted small mb-0">Order details & item status</p>
        </div>

        <a href="{{ route('franchise.orders.index') }}"
           class="btn btn-light">
            ← Back to Orders
        </a>
    </div>

    {{-- SUMMARY --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Amount</div>
                    <div class="fs-4 fw-bold">
                         {{ company_info('currency', '$') }}  {{ number_format($order->order_amount,2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total BV</div>
                    <div class="fs-4 fw-bold">
                        {{ $order->order_bv }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Status</div>
                    <div>
                        @if($order->status == 0)
                            <span class="badge bg-light-warning text-warning px-3 py-2">
                                Pending
                            </span>
                        @elseif($order->status == 1)
                            <span class="badge bg-light-success text-success px-3 py-2">
                                Approved
                            </span>
                        @elseif($order->status == 2)
                            <span class="badge bg-light-danger text-danger px-3 py-2">
                                Rejected
                            </span>
                        @else
                            <span class="badge bg-light-primary text-primary px-3 py-2">
                                Partial
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            Order Items
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-center">Item Status</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($order->items as $i => $item)
                    <tr>
                        <td>{{ $i+1 }}</td>

                        <td class="fw-semibold">
                            {{ $item->name }}
                        </td>

                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>

                        <td class="text-end">
                             {{ company_info('currency', '$') }}  {{ number_format($item->sub_total / max($item->quantity,1),2) }}
                        </td>

                        <td class="text-end fw-bold">
                             {{ company_info('currency', '$') }}  {{ number_format($item->sub_total,2) }}
                        </td>

                        <td class="text-center">
                            @if($item->status == 0)
                                <span class="badge bg-light-warning text-warning">
                                    Pending
                                </span>
                            @elseif($item->status == 1)
                                <span class="badge bg-light-success text-success">
                                    Approved
                                </span>
                            @else
                                <span class="badge bg-light-danger text-danger">
                                    Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Order Total</th>
                        <th class="text-end">
                             {{ company_info('currency', '$') }}  {{ number_format($order->order_amount,2) }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<style>
.bg-light-warning{background:#fff8dd!important;}
.bg-light-success{background:#e8fff3!important;}
.bg-light-danger{background:#fff5f8!important;}
.bg-light-primary{background:#f1faff!important;}
</style>
@endsection