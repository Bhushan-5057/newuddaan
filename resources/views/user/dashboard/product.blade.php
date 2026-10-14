        <div class="col-lg-5 col-xxl-3 eshop-cards-container">

            <div class="row">

                {{-- Total Purchase Card --}}

                <div class="col-6 col-md-3 col-lg-6">

                    <div class="card shadow-sm">

                        <span class="bg-primary h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">

                            <i class="ph ph-currency-circle-dollar f-s-24"></i>

                        </span>

                        <div class="card-body eshop-cards">

                            <span class="ripple-effect"></span>

                            <div class="overflow-hidden">

                                <h3 class="text-primary mb-0">

                                    {{ company_info('currency_bv', '$') }}{{ number_format($totalPurchase, 0) }}</h3>

                                <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Total Purchase</p>

                                <span class="badge bg-light-primary">E-shop</span>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Canceled Orders Card --}}

                <div class="col-6 col-md-3 col-lg-6">

                    <div class="card shadow-sm">

                        <span class="bg-warning h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">

                            <i class="ph-duotone ph-certificate f-s-24"></i>

                        </span>

                        <div class="card-body eshop-cards">

                            <span class="ripple-effect"></span>

                            <h3 class="text-warning mb-0">{{ $totalBV }}</h3>

                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Total BV Points</p>

                            <span class="badge bg-light-warning">BV Wallet</span>

                        </div>

                    </div>

                </div>



                {{-- Wallet BV Card --}}

                <div class="col-6 col-md-3 col-lg-6">

                    <div class="card shadow-sm">

                        <span class="bg-danger h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">

                            <i class="ph ph-x-circle f-s-24"></i>

                        </span>

                        <div class="card-body eshop-cards">

                            <span class="ripple-effect"></span>

                            <h3 class="text-danger mb-0 txt-ellipsis-1">{{ $canceledOrders }}</h3>

                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Canceled Orders</p>

                            <span class="badge bg-light-dark"> History</span>

                        </div>

                    </div>

                </div>



                {{-- Active Status Card --}}

                <div class="col-6 col-md-3 col-lg-6">

                    <div class="card shadow-sm">

                        <span class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">

                            <i class="ph-duotone ph-user-circle-plus f-s-24"></i>

                        </span>

                        <div class="card-body eshop-cards">

                            <span class="ripple-effect"></span>

                            <h3 class="text-success mb-0">Active</h3>

                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Account Status</p>

                            <span class="badge bg-light-success">Verified</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <div class="col-lg-7 col-xxl-6">

            @if ($order) {{-- જો ઓર્ડર હોય તો જ આ સેક્શન બતાવવું --}}

                <div class="card">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-sm-6 position-relative">

                                <div class="location-container">

                                    <div class="glass-effect-box position-absolute bottom-0">

                                        <div class="d-flex align-items-center flex-wrap">

                                            <div

                                                class="w-65 h-65 overflow-hidden position-absolute top-0 start-0 d-flex-center">

                                                <img alt="order icon" class="img-fluid"

                                                    src="{{ asset('user/assets/images/dashboard/ecommerce-dashboard/order1.gif') }}">

                                            </div>

                                            <div class="flex-grow-1 ps-5">

                                                <p class="fw-medium mb-0">OrderId</p>

                                                <h6 class="text-primary mb-0 txt-ellipsis-1">{{ $order->tx_id }}</h6>

                                            </div>

                                            {{-- ડાયનેમિક સ્ટેટસ બેજ --}}

                                            <span class="badge text-bg-primary f-s-10 ms-2">•

                                                {{ $order->status_label }}</span>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            

                            <div class="col-sm-6 cart-side-card mt-3 mt-sm-0">

                                <div class="mb-2">

                                    <h6 class="text-dark mb-0 txt-ellipsis-1">

                                        Delivery Date :

                                        {{ $order->date_approve ? $order->date_approve->format('d M Y') : 'Pending' }}

                                    </h6>

                                </div>

                                <div class="table-responsive">

                                    <table class="table cart-side-table mb-0">

                                        <tbody>

                                            <tr class="total-price">

                                                <th>Sub Total :</th>

                                                <th class="text-end">

                                                    <span>{{ company_info('currency', '$') }}{{ number_format($order->order_mrp, 2) }}</span>

                                                </th>

                                            </tr>

                                            <tr>

                                                <td>

                                                    <p class="mb-0 txt-ellipsis-1">Total Points:</p>

                                                </td>

                                                <td class="text-end text-success">BV: {{ $order->order_bv }} </td>

                                            </tr>

                                            <tr class="total-price">

                                                <th>

                                                    <p class="mb-0 txt-ellipsis-1">Grand Total :</p>

                                                </th>

                                                <th class="text-end">

                                                    <span

                                                        id="cart-total">{{ company_info('currency', '$') }}{{ number_format($order->order_amount, 2) }}</span>

                                                </th>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <p class="f-w-500 mb-0 txt-ellipsis-1"> Support:

                                    <a href="#"

                                        class="link-primary text-d-underline">support.order/{{ $order->tx_id }}</a>

                                </p>

                            </div>

                        </div>

                    </div>

                </div>







                {{-- ઓર્ડર ટાઈમલાઈન - સ્ટેટસ મુજબ કલર અને ડેટા બદલાશે --}}

                <div class="card">

                    <div class="card-body">

                        <ul class="app-side-timeline shipping-timeline">

                            {{-- ૧. Order Placed - હંમેશા કમ્પ્લીટ હશે --}}

                            <li class="side-timeline-section w-100 right-side complete-step">

                                <div class="side-timeline-icon">

                                    <span class="bg-primary h-35 w-35 d-flex-center b-r-50">

                                        <i class="ph-fill ph-shopping-bag-open f-s-18"></i>

                                    </span>

                                </div>

                                <div class="timeline-content p-0">

                                    <div>

                                        <h6 class="f-s-15 mb-2 txt-ellipsis-1">Order Placed</h6>

                                        <p class="mb-0 text-dark-800 f-w-400">

                                            {{ $order->added_on->format('M d/y') }}

                                            <span

                                                class="text-primary f-w-500">{{ $order->added_on->format('h:i A') }}</span>

                                        </p>

                                    </div>

                                </div>

                            </li>



                            {{-- ૨. Order Confirmed (Packing Stage) --}}

                            <li

                                class="side-timeline-section w-100 right-side {{ $order->date_accept ? 'complete-step' : '' }}">

                                <div class="side-timeline-icon">

                                    <span

                                        class="{{ $order->date_accept ? 'bg-primary' : 'bg-light-secondary text-dark-400' }} h-35 w-35 d-flex-center b-r-50">

                                        <i class="ph-fill ph-seal-check f-s-18"></i>

                                    </span>

                                </div>

                                <div class="timeline-content p-0">

                                    <div>

                                        <h6 class="f-s-15 mb-2 txt-ellipsis-1">Order Confirmed</h6>

                                        @if ($order->date_accept)

                                            <p class="mb-0 text-dark-800 f-w-400">

                                                {{ $order->date_accept->format('M d/y') }}

                                                <span

                                                    class="text-primary f-w-500">{{ $order->date_accept->format('h:i A') }}</span>

                                            </p>

                                        @else

                                            <p class="mb-0 text-muted">Awaiting confirmation</p>

                                        @endif

                                    </div>

                                </div>

                            </li>



                            {{-- ૩. Shipped (Out for Delivery Stage) --}}

                            <li

                                class="side-timeline-section w-100 right-side {{ $order->date_dispatch ? 'complete-step' : '' }}">

                                <div class="side-timeline-icon">

                                    <span

                                        class="{{ $order->date_dispatch ? 'bg-primary' : 'bg-light-secondary text-dark-400' }} h-35 w-35 d-flex-center b-r-50">

                                        <i class="ph-fill ph-truck f-s-18"></i>

                                    </span>

                                </div>

                                <div class="timeline-content p-0">

                                    <div>

                                        <h6 class="f-s-15 mb-2 txt-ellipsis-1">Shipped via Courier</h6>

                                        @if ($order->date_dispatch)

                                            <p class="mb-0 text-dark-800 f-w-400">

                                                {{ $order->date_dispatch->format('M d/y') }}

                                                <span

                                                    class="text-primary f-w-500">{{ $order->date_dispatch->format('h:i A') }}</span>

                                            </p>

                                        @else

                                            <p class="mb-0 text-muted">Not yet shipped</p>

                                        @endif

                                    </div>

                                </div>

                            </li>



                            {{-- ૪. Delivered (Completed Stage) --}}

                            <li

                                class="side-timeline-section w-100 right-side {{ $order->date_approve ? 'complete-step' : '' }}">

                                <div class="side-timeline-icon">

                                    <span

                                        class="{{ $order->date_approve ? 'bg-primary' : 'bg-light-secondary text-dark-400' }} h-35 w-35 d-flex-center b-r-50">

                                        <i class="ph-fill ph-house f-s-18"></i>

                                    </span>

                                </div>

                                <div class="timeline-content p-0">

                                    <div>

                                        <h6 class="f-s-15 mb-2 txt-ellipsis-1">Delivered</h6>

                                        @if ($order->date_approve)

                                            <p class="mb-0 text-dark-800 f-w-400">

                                                {{ $order->date_approve->format('M d/y') }}

                                                <span

                                                    class="text-primary f-w-500">{{ $order->date_approve->format('h:i A') }}</span>

                                            </p>

                                        @else

                                            <p class="mb-0 text-muted">Expected soon</p>

                                        @endif

                                    </div>

                                </div>

                            </li>

                        </ul>

                    </div>

                </div>

            @else

                {{-- જો ઓર્ડર ન હોય તો આ મેસેજ બતાવવો --}}



                <div class="card">

                    <div class="card-body text-center p-5">

                        <i class="ph ph-shopping-cart f-s-48 text-muted"></i>

                        <h5 class="mt-3">No Orders Found</h5>

                        <p class="text-muted small">You haven't placed any orders yet. Start shopping to see your

                            tracking details here!</p>

                        <a href="{{ route('product.shop') }}" class="btn btn-primary btn-sm rounded-pill px-4">Go to

                            E-shop</a>

                    </div>

                </div>

            @endif

        </div>

        <div class="col-sm-6 col-lg-4 col-xxl-3">

            <ul class="box-list mb-4">

                <li class="b-s-4-primary">

                    <h5>Recent Purchases</h5>

                </li>



                @forelse($recentPurchases as $item)

                    @if ($item->product)

                        <li class="d-flex align-items-center justify-content-between gap-2">

                            <div

                                class="b-1-light bg-primary-200 p-1 h-40 w-40 d-flex-center b-r-12 flex-shrink-0 overflow-hidden box-list-img">

                                <img alt="{{ $item->product->name }}" class="img-fluid"

                                    src="{{ asset('storage/' . $item->product->imgs) }}">

                            </div>



                            <div class="mg-s-45 flex-grow-1">

                                <h6 class="mb-0 text-dark-800 f-w-500 txt-ellipsis-1">

                                    {{ $item->product->name }}

                                </h6>

                                <p class="text-secondary-800 mb-0">

                                    {{ optional($item->added_on)->format('d M, Y') }}

                                </p>

                            </div>



                            <span class="badge bg-light-primary">

                                {{ company_info('currency', '$') }}{{ number_format($item->price, 2) }}

                            </span>

                        </li>

                    @endif

                @empty

                    <li class="text-center p-3">

                        <p class="text-muted mb-0">No recent purchases found.</p>

                    </li>

                @endforelse

            </ul>

        </div>

