@extends('user.layouts.app')

@section('content')
@auth('web')
@php
    $sidebarCarts = \App\Models\Cart::with('product')
        ->where('u_code', auth()->user()->id)
        ->get();
    $totalItems = $sidebarCarts->sum('quantity');
    $totalPrice = $sidebarCarts->sum(fn($i) => $i->dp * $i->quantity);
@endphp

<div class="row m-1">
    <div class="col-12 ">
        <h4 class="main-title">Cart</h4>
        <ul class="app-line-breadcrumbs mb-3">
            <li class="">
                <a class="f-s-14 f-w-500" href="{{ route('dashboard.user') }}">
                    <span>
                        <i class="ph-duotone ph-stack f-s-16"></i> Dashboard
                    </span>
                </a>
            </li>
            <li>
                <a class="f-s-14 f-w-500" href="#">E-shop</a>
            </li>
            <li class="active">
                <a class="f-s-14 f-w-500" href="#">Cart (<span id="sidebar-total-items">{{ $totalItems }}</span>)</a>
            </li>
        </ul>
    </div>
</div>
<!-- Breadcrumb end -->

<!-- cart start -->
<div class="row cart-table">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-0">
                <!-- table -->
                <div class="app-scroll table-responsive app-datatable-default">
                    <table class="table cart-product-table align-middle" id="example">
                        <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidebarCarts as $item)
                                <tr data-id="{{ $item->id }}">
                                    <th scope="row">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="b-1-light bg-primary-200 p-1 h-45 w-45 d-flex-center b-r-12 flex-shrink-0 overflow-hidden box-list-img">
                                                <a href="{{ route('product.details', $item->product->slug) }}">
                                                    <img alt="{{ $item->product->name }}" class="img-fluid" src="{{ asset('storage/' . $item->product->imgs) }}">
                                                </a>
                                            </span>

                                            <div class="cart-details">
                                                <p class="fs-6">
                                                    <a href="{{ route('product.details', $item->product->slug) }}" class="text-dark">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </p>
                                                {{-- જો કલર કે સાઈઝ ડાયનેમિક હોય તો અહિયાં ઉમેરી શકો છો --}}
                                            </div>
                                        </div>
                                    </th>
                                    <td>₹{{ $item->dp }}</td>
                                    <td>
                                        <div class="d-flex align-items-center" data-cart-id="{{ $item->id }}">
                                            <button type="button" class="btn btn-danger h-35 w-35 d-flex-center b-r-6 p-0 decrement" onclick="updateCheckoutQty({{ $item->id }}, 'minus')">
                                                -
                                            </button>
                                            <input class="h-35 w-35 ms-1 me-1 border b-r-6 text-center count" type="text" id="sidebar-qty-{{ $item->id }}" value="{{ $item->quantity }}" readonly>
                                            <button type="button" class="btn btn-primary h-35 w-35 d-flex-center b-r-6 p-0 increment" onclick="updateCheckoutQty({{ $item->id }}, 'plus')">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="sidebar-item-total-{{ $item->id }}">
                                            ₹{{ number_format($item->dp * $item->quantity, 2) }}
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary icon-btn b-r-4" type="button">
                                            <i class="ti ti-heart"></i>
                                        </button>
                                        <button class="btn btn-outline-danger icon-btn b-r-4 delete-btn" type="button" onclick="removeSidebarItem({{ $item->id }})">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4">Your cart is empty</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- table end -->
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Price Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table cart-side-table mb-0">
                                <tbody>
                                    <tr class="total-price">
                                        <th>Sub Total :</th>
                                        <td id="sidebar-total-price" class="text-end f-w-600">₹{{ number_format($totalPrice, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-primary w-100 text-center">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection