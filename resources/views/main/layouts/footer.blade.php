<!--=====================================
             FOOTER PART START
=======================================-->
<footer class="footer-part">
    <div class="container">
        <div class="row">
 <div class="col-sm-6 col-xl-3">
    <div class="footer-widget">
        <a class="footer-logo" href="{{ route('home') }}">
            <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ company_info('company_name') }} Logo">
        </a>
        
        <p class="footer-desc">
            <strong>GSTIN:</strong> {{ company_info('company_gst') }}
        </p>
        
        <p class="footer-desc">
            Premium Ayurvedic wellness products for healthy and balanced living.
        </p>
        
        <ul class="footer-social">
            @php
                $socialLinks = [
                    'facebook' => ['icon' => 'icofont-facebook', 'color' => '#1877f2'],
                    'twitter' => ['icon' => 'icofont-twitter', 'color' => '#1da1f2'],
                    'instagram' => ['icon' => 'icofont-instagram', 'color' => '#e4405f'],
                    'linkedin' => ['icon' => 'icofont-linkedin', 'color' => '#0077b5'],
                    'youtube' => ['icon' => 'icofont-youtube', 'color' => '#ff0000'],
                    'telegram' => ['icon' => 'icofont-telegram', 'color' => '#0088cc'],
                    'whatsapp' => ['icon' => 'icofont-whatsapp', 'color' => '#25d366'],
                    'pinterest' => ['icon' => 'icofont-pinterest', 'color' => '#bd081c'],
                ];
            @endphp
            
            @foreach($socialLinks as $platform => $data)
                @if(company_info("company_{$platform}_link"))
                <li>
                    <a href="{{ company_info("company_{$platform}_link") }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="{{ $data['icon'] }}"
                       style="transition: all 0.3s;">
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
    <div class="col-sm-6 col-xl-3">
    <div class="footer-widget contact">
        <h3 class="footer-title">contact us</h3>
        <ul class="footer-contact">
            <li>
                <i class="icofont-ui-email"></i>
                <p>
                    <a href="mailto:{{ company_info('company_email') }}" style="color: inherit; text-decoration: none;">
                        <span>{{ company_info('company_email') }}</span>
                    </a>
                </p>
            </li>
            <li>
                <i class="icofont-ui-touch-phone"></i>
                <p>
                    <a href="tel:{{ company_info('company_mobile') }}" style="color: inherit; text-decoration: none;">
                        <span>{{ company_info('company_mobile') }}</span>
                    </a>
                </p>
            </li>
            <li>
                <i class="icofont-location-pin"></i>
                <a href="{{ company_info('map_link') }}" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                    <p>
                        <span>{{ company_info('company_address') }}</span>
                    </p>
                </a>
            </li>
        </ul>
    </div>
</div>

            <div class="col-sm-6 col-xl-3">
                <div class="footer-widget">
                    <h3 class="footer-title">quick Links</h3>
                    <div class="footer-links">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                             <li><a href="{{ route('faq') }}">FAQs</a></li> 
                        </ul>
                        <ul>

         
                
                 <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('terms.conditions') }}">Terms & Conditions</a></li>
                <li><a href="{{ route('refund.policy') }}">Refund & Cancellation Policy</a></li>
                <li><a href="{{ route('shipping.policy') }}">Shipping & Delivery Policy</a></li>
                           
                        </ul>
                    </div>
                </div>
            </div>
            
            <style>
                .news-marquee-box {
    height: 240px;
    overflow: hidden;
    position: relative;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 10px;
}

.news-marquee-content {
    position: absolute;
    width: 100%;
    animation: scrollNews 15s linear infinite;
}

.news-marquee-box:hover .news-marquee-content {
    animation-play-state: paused;
}

@keyframes scrollNews {
    0% {
        transform: translateY(100%);
    }

    100% {
        transform: translateY(-100%);
    }
}
            </style>

        <div class="col-sm-6 col-xl-3">
    <div class="footer-widget">
        <h3 class="footer-title">Latest News</h3>

        <div class="news-marquee-box">
            <div class="news-marquee-content">
                {!! nl2br(company_info('news')) !!}
            </div>
        </div>

    </div>
</div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="footer-bottom">
                    <p class="footer-copytext">
                        &copy; All Copyrights Reserved by
                        <a href="{{ route('home') }}">{{ company_info('company_name') }}</a>
                    </p>
                    <!--<div class="footer-card">-->
                    <!--    <a href="#"><img src="{{ asset('main/images/payment/jpg/01.jpg') }}" alt="payment"></a>-->
                    <!--    <a href="#"><img src="{{ asset('main/images/payment/jpg/02.jpg') }}" alt="payment"></a>-->
                    <!--    <a href="#"><img src="{{ asset('main/images/payment/jpg/03.jpg') }}" alt="payment"></a>-->
                    <!--    <a href="#"><img src="{{ asset('main/images/payment/jpg/04.jpg') }}" alt="payment"></a>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="product-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="modal-close icofont-close" data-bs-dismiss="modal"></button>
            <div class="product-view">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="view-gallery">
                            <div class="view-label-group">
                                {{-- <label class="view-label new" id="modal-label-new">new</label> --}}
                                <label class="view-label off" id="modal-discount-badge"></label>
                            </div>
                            <div class="preview-slider">
                                <img id="modal-main-img" src="" class="img-fluid w-100"
                                    style="height: 350px; object-fit: contain;">
                            </div>
                            <ul id="modal-gallery-thumbs"
                                class="thumb-slider d-flex gap-2 mt-3 list-unstyled overflow-auto">
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="view-details">
                            <h3 class="view-name">
                                <a id="modal-product-link" href=""></a>
                            </h3>
                            <div class="view-meta">
                                <p>SKU:<span id="modal-sku"></span></p>
                               
                                <p>
                                    BRAND:
                                    <a href="#" class="brand-text">
                                        {{ company_info('brand') }}
                                    </a>
                                </p>
                                
                                <style>
                                .brand-text{
                                    background: linear-gradient(
                                        to bottom,
                                        #1b8fd3 0%,
                                        #1b8fd3 45%,
                                        #10a36d 55%,
                                        #10a36d 100%
                                    );
                                    
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                
                                    font-weight: 800;
                                    /*text-decoration: underline;*/
                                    text-decoration-color: #1b8fd3;
                                    text-underline-offset: 2px;
                                }
                                </style>
                            </div>
                    
                            <h3 class="view-price">
                                <del id="modal-mrp"></del>
                                <span id="modal-dp"></span>
                            </h3>
                            <p class="view-desc" id="modal-desc"></p>

                            <div class="view-list-group">
                                <label class="view-list-title">Points:</label>
                                <ul class="view-tag-list">
                                    <li><a href="javascript:void(0)">BV: <span id="modal-bv"></span></a></li>
                                  
                                </ul>
                            </div>

                  
                   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="login-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="modal-close icofont-close" data-bs-dismiss="modal"></button>
            <div class="product-view">
                <div class="row">
                    <div class="col-12">
                        <div class="view-details">
                            <h3 class="view-name">Login to Continue</h3>

                            <form action="{{ route('login') }}" method="POST" class="mt-4">
                                @csrf

                                <div class="form-group mb-3">
                                    <input type="text" name="email_username" class="form-control"
                                        placeholder="Enter Email or Username" required
                                        value="{{ old('email_username') }}">
                                </div>

                                <div class="form-group mb-3 position-relative">
                                    <input type="password" name="password" id="login-password" class="form-control"
                                        placeholder="Password" required>

                                    <span class="eye-icon" onclick="togglePassword()"
                                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #777;">
                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                    </span>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>

                                <button type="submit" class="btn btn-inline w-100">
                                    <span>Login Now</span>
                                </button>

                                <p class="mt-3 text-center">Don't have an account?
                                    <a href="{{ route('register') }}" class="text-success">Register Here</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--=====================================
              FOOTER PART END
=======================================-->




<!--=====================================
            JS LINK PART START
=======================================-->

<style>
    .product-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .product-qty-group {
        display: flex;
        background: #f5f5f5;
        border-radius: 5px;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    .product-qty-group button {
        width: 35px;
        height: 35px;
        background: #eee;
        border: none;
        font-size: 14px;
        cursor: pointer;
    }

    .product-qty-group input {
        width: 45px;
        text-align: center;
        border: none;
        background: #fff;
        font-weight: bold;
    }

    /* સક્સેસ હાઈલાઈટ */
    .already-in-cart {
        background: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }
</style>



<script>
    function togglePassword() {
        const passwordField = document.getElementById("login-password");
        const toggleIcon = document.getElementById("toggleIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('main/vendor/bootstrap/bootstrap.min.js') }}"></script>

<script src="{{ asset('main/vendor/countdown/countdown.min.js') }}"></script>
<script src="{{ asset('main/vendor/niceselect/nice-select.min.js') }}"></script>
<script src="{{ asset('main/vendor/slickslider/slick.min.js') }}"></script>
<script src="{{ asset('main/vendor/venobox/venobox.min.js') }}"></script>
<!-- VENDOR -->
{{-- <script src="{{ asset('main/vendor/bootstrap/jquery-1.12.4.min.js') }}"></script> --}}
{{-- <script src="{{ asset('main/vendor/bootstrap/popper.min.js') }}"></script> --}}
{{-- <script src="{{ asset('main/vendor/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('main/vendor/countdown/countdown.min.js') }}"></script>
<script src="{{ asset('main/vendor/niceselect/nice-select.min.js') }}"></script>
<script src="{{ asset('main/vendor/slickslider/slick.min.js') }}"></script>
<script src="{{ asset('main/vendor/venobox/venobox.min.js') }}"></script> --}}
<script>

    
    function toggleWishlist(element, productId) {

    $.ajax({
        url: "{{ route('wishlist.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            product_id: productId
        },

        success: function(response) {

            if (response.status === 'login') {

                $('#login-modal').modal('show');

            } else {

                let icon = $(element).find('i');

                if (response.status === 'added') {

                    $(element).addClass('active');

                    icon.removeClass('far').addClass('fas');

                } else {

                    $(element).removeClass('active');

                    icon.removeClass('fas').addClass('far');
                }

                // ✅ Header Refresh
                if (typeof refreshHeader === "function") {
                    refreshHeader();
                }

                // ✅ Mobile Count Refresh
                if (typeof refreshCounts === "function") {
                    refreshCounts();
                }

                // ✅ Alert
                showGlobalAlert(
                    response.message,
                    response.status === 'added'
                        ? 'success'
                        : 'error'
                );
            }
        }
    });
}


    // 1️⃣ Plus / Minus handler (NO ID issue)
    function changeQty(type, el) {
        let box = el.closest('.qty-box');
        let input = box.querySelector('.qty-input');
        let qty = parseInt(input.value) || 1;

        if (type === 'plus') {
            input.value = qty + 1;
        } else if (type === 'minus' && qty > 1) {
            input.value = qty - 1;
        }
    }

    // 2️⃣ Add to cart with correct qty
    function addToCartAjax(el) {

        // 1️⃣ nearest qty-box (no matter where button is)
        let qtyBox = el.closest(
            '.details-add-group, .product-action-group, .view-add-group, .feature-content, .product-content'
        )?.querySelector('.qty-box');

        if (!qtyBox) {
            console.error('Qty box not found for Add to Cart');
            return;
        }

        let productId = qtyBox.dataset.productId;
        let qty = qtyBox.querySelector('.qty-input')?.value || 1;

        $.ajax({
            url: "{{ route('cart.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId,
                quantity: qty
            },
            success: function(response) {

                if (response.status === 'login') {
                    $('#login-modal').modal('show');
                    return;
                }

                // ✅ Alert everywhere
                if (typeof showGlobalAlert === "function") {
                    showGlobalAlert(response.message, 'success');
                } else {
                    alert(response.message);
                }

                // ✅ Header / cart count refresh
                if (typeof refreshHeader === "function") refreshHeader();
                if (typeof refreshCounts === "function") refreshCounts();
                if (typeof refreshCartSidebar === "function") refreshCartSidebar();

                // ✅ Optional UX feedback
                let oldHtml = el.innerHTML;
                el.innerHTML = '<i class="fas fa-check"></i> Added';
                el.disabled = true;

                setTimeout(() => {
                    el.innerHTML = oldHtml;
                    el.disabled = false;
                }, 1200);
            },
            error: function() {
                alert("Something went wrong!");
            }
        });
    }

    function updateSidebarQty(cartId, type) {

        let qtyInput = document.getElementById('sidebar-qty-' + cartId);
        let currentQty = parseInt(qtyInput.value) || 1;

        if (type === 'minus' && currentQty <= 1) return;

        let newQty = type === 'plus' ? currentQty + 1 : currentQty - 1;

        $.ajax({
            url: "{{ route('cart.update') }}", // 🔥 create this route
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                cart_id: cartId,
                quantity: newQty
            },
            success: function(res) {

                // update qty
                qtyInput.value = newQty;

                // update item total
                document.getElementById('sidebar-total-' + cartId)
                    .innerText = '₹' + res.item_total;

                // update header + sidebar totals
                if (typeof refreshHeader === "function") refreshHeader();
                if (typeof refreshCounts === "function") refreshCounts();
                if (typeof refreshCartSidebar === "function") refreshCartSidebar();

                // update sidebar footer total
                document.querySelector('.checkout-price').innerText = '₹' + res.cart_total;
                document.querySelector('.cart-total span').innerText = 'total item (' + res.cart_items +
                    ')';
            },
            error: function() {
                alert("Unable to update cart");
            }
        });
    }

    function removeSidebarItem(cartId) {
        $.ajax({
            url: "{{ url('/cart/remove') }}/" + cartId,
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {

                // remove item
                document.querySelector('.cart-item[data-id="' + cartId + '"]')?.remove();

                // update header total
                document.querySelector('.cart-total span').innerText =
                    'total item (' + res.cart_items + ')';

                document.querySelector('.checkout-price').innerText =
                    '₹' + res.cart_total;

                if (typeof refreshHeader === "function") refreshHeader();
                if (typeof refreshCartSidebar === "function") refreshCartSidebar();

                // empty cart
                if (res.cart_items == 0) {
                    document.querySelector('.cart-list').innerHTML =
                        '<li class="text-center mt-5">Your cart is empty</li>';
                }

                if (typeof showGlobalAlert === "function") {
                    showGlobalAlert(res.message, 'success');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Unable to remove item');
            }
        });
    }

    $(document).ready(function() {


        updateHeaderCounts();

        function updateHeaderCounts() {
            $.ajax({
                url: "{{ route('cart.wishlist.count') }}",
                method: "GET",
                success: function(response) {

                    // wishlist count
                    $('.ajax-wish-count').text(response.wish_count);

                    // cart count (9+ logic)
                    let cartDisplay = response.cart_count > 9 ? '9+' : response.cart_count;
                    $('.ajax-cart-count').text(cartDisplay);

                    // total price
                    let currency = "{{ company_info('currancy', '₹') }}";
                    $('.ajax-cart-total').text(
                        currency + ' ' + parseFloat(response.total_price).toFixed(2)
                    );
                }
            });
        }

        // ✅ global expose (ONLY this)
        window.refreshHeader = updateHeaderCounts;
    });


    $(document).ready(function() {
        updateMobileCounts();
        // ફંક્શન જે કાઉન્ટ અપડેટ કરશે
        function updateMobileCounts() {
            $.ajax({
                url: "{{ route('cart.wishlist.count') }}", // તમારે આ રૂટ બનાવવો પડશે
                method: "GET",
                success: function(response) {
                    $('.ajax-cart-count').text(response.cart_count);
                    $('.ajax-wish-count').text(response.wish_count);
                    $('.ajax-compare-count').text(response.compare_count || 0);
                }
            });
        }

        // જ્યારે પણ 'Add to Cart' કે 'Wishlist' બટન દબાય, ત્યારે આ ફંક્શન ફરી કોલ કરવું
        window.refreshCounts = updateMobileCounts;
    });
    
    

    
    function updateCheckoutQty(cartId, type)
    {
    
        $.ajax({
    
            url: "{{ route('cart.update') }}",
    
            method: "POST",
    
            data: {
    
                _token: "{{ csrf_token() }}",
    
                cart_id: cartId,
    
                type: type
            },
    
            success: function(res)
            {
    
                /*
                |--------------------------------------------------------------------------
                | QTY INPUT
                |--------------------------------------------------------------------------
                */
    
      /*
    |--------------------------------------------------------------------------
    | CHECKOUT INPUT
    |--------------------------------------------------------------------------
    */
    
    $('#checkout-page-qty-' + cartId)
        .val(res.quantity);
    
    /*
    |--------------------------------------------------------------------------
    | SIDEBAR INPUT
    |--------------------------------------------------------------------------
    */
    
    $('#sidebar-qty-' + cartId)
        .val(res.quantity);
    
                /*
                |--------------------------------------------------------------------------
                | SIDEBAR ROW TOTAL
                |--------------------------------------------------------------------------
                */
    
                $('#sidebar-item-total-' + cartId)
                    .html(
    
                        '₹' +
    
                        parseFloat(
                            res.item_total
                        ).toLocaleString('en-IN', {
    
                            minimumFractionDigits: 2,
    
                            maximumFractionDigits: 2
                        })
                    );
    
                /*
                |--------------------------------------------------------------------------
                | SIDEBAR TOTAL ITEMS
                |--------------------------------------------------------------------------
                */
    
                $('#sidebar-total-items')
                    .html(res.cart_items);
    
                /*
                |--------------------------------------------------------------------------
                | SIDEBAR TOTAL PRICE
                |--------------------------------------------------------------------------
                */
    
                $('#sidebar-total-price')
                    .html(
    
                        '₹' +
    
                        parseFloat(
                            res.cart_total
                        ).toLocaleString('en-IN', {
    
                            minimumFractionDigits: 2,
    
                            maximumFractionDigits: 2
                        })
                    );
    
                /*
                |--------------------------------------------------------------------------
                | CHECKOUT PAGE
                |--------------------------------------------------------------------------
                */
    
                if ($('#checkout-grand').length)
                {
    
                    $('#checkout-total-' + cartId)
                        .html(
    
                            '₹' +
    
                            parseFloat(
                                res.item_total
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-taxable')
                        .html(
                            '₹' +
                            parseFloat(
                                res.taxable
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-gst')
                        .html(
                            '₹' +
                            parseFloat(
                                res.total_gst
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-cgst')
                        .html(
                            '₹' +
                            parseFloat(
                                res.cgst
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-sgst')
                        .html(
                            '₹' +
                            parseFloat(
                                res.sgst
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-subtotal')
                        .html(
                            '₹' +
                            parseFloat(
                                res.cart_total
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-grand')
                        .html(
                            '₹' +
                            parseFloat(
                                res.cart_total
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#finalize-total-text')
                        .html(
                            '₹' +
                            parseFloat(
                                res.cart_total
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
    
                    $('#checkout-bvpv')
                        .html(
                            parseFloat(
                                res.total_bv
                            ).toLocaleString('en-IN', {
    
                                minimumFractionDigits: 2,
    
                                maximumFractionDigits: 2
                            })
                        );
                }
            }
        });
    }




    function refreshCartSidebar() {
        $('#cart-sidebar-wrapper').load("{{ route('cart.sidebar') }}");
    }

    let headerTimer;
    window.refreshHeader = function() {
        clearTimeout(headerTimer);
        headerTimer = setTimeout(updateHeaderCounts, 300);
    };


    
    function removeCheckoutItem(cartId)
    {
    
        $.ajax({
    
            url:
                "{{ url('/cart/remove') }}/"
                + cartId,
    
            method: "POST",
    
            data: {
    
                _token:
                    "{{ csrf_token() }}"
            },
    
            success: function(res)
            {
    
                /*
                |--------------------------------------------------------------------------
                | REMOVE ROW
                |--------------------------------------------------------------------------
                */
    
                $('tr[data-cart-row="' +
                    cartId + '"]').remove();
    
                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */
    
                $('#checkout-subtotal').html(
    
                    '₹' +
    
                    parseFloat(
                        res.cart_total
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                /*
                |--------------------------------------------------------------------------
                | GRAND TOTAL
                |--------------------------------------------------------------------------
                */
    
                $('#checkout-grand').html(
    
                    '₹' +
    
                    parseFloat(
                        res.cart_total
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                /*
                |--------------------------------------------------------------------------
                | FINALIZE ORDER
                |--------------------------------------------------------------------------
                */
    
                $('#finalize-total-text').html(
    
                    '₹' +
    
                    parseFloat(
                        res.cart_total
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                /*
                |--------------------------------------------------------------------------
                | BV
                |--------------------------------------------------------------------------
                */
    
                $('#checkout-bvpv').html(
    
                    parseFloat(
                        res.total_bv
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                /*
                |--------------------------------------------------------------------------
                | GST
                |--------------------------------------------------------------------------
                */
    
                $('#checkout-taxable').html(
    
                    '₹' +
    
                    parseFloat(
                        res.taxable
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                $('#checkout-gst').html(
    
                    '₹' +
    
                    parseFloat(
                        res.total_gst
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                $('#checkout-cgst').html(
    
                    '₹' +
    
                    parseFloat(
                        res.cgst
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                $('#checkout-sgst').html(
    
                    '₹' +
    
                    parseFloat(
                        res.sgst
                    ).toLocaleString('en-IN', {
    
                        minimumFractionDigits: 2,
    
                        maximumFractionDigits: 2
                    })
                );
    
                /*
                |--------------------------------------------------------------------------
                | SIDEBAR
                |--------------------------------------------------------------------------
                */
    
                $('#sidebar-total-items')
                    .html(res.cart_items);
    
                $('#sidebar-total-price')
                    .html(
    
                        '₹' +
    
                        parseFloat(
                            res.cart_total
                        ).toLocaleString('en-IN', {
    
                            minimumFractionDigits: 2,
    
                            maximumFractionDigits: 2
                        })
                    );
    
                /*
                |--------------------------------------------------------------------------
                | EMPTY CART
                |--------------------------------------------------------------------------
                */
    
                if (res.cart_items == 0)
                {
    
                    location.reload();
                }
            },
    
            error: function(xhr)
            {
    
                console.log(xhr.responseText);
    
                alert(
                    'Unable to remove item'
                );
            }
        });
    }





    // function showGlobalAlert(message, type) {
    //     let alertHtml = `<div class="custom-alert alert-${type === 'success' ? 'success' : 'danger'}">
    // <i class="fas fa-check-circle"></i>
    // <span>${message}</span> </div>`;
    //     $('#flash-container').append(alertHtml);

    //     // 10 સેકન્ડમાં ઓટો રીમુવ
    //     setTimeout(() => {
    //         $('.custom-alert').fadeOut(500, function() {
    //             $(this).remove();
    //         });
    //     }, 10000);
    // }
    
    
    function showGlobalAlert(message, type) {

    Swal.fire({
        title: type === 'success' ? 'Success!' : 'Error!',
        text: message,
        icon: type === 'success' ? 'success' : 'error',
        confirmButtonText: 'OK',
       
    });

    let alertHtml = `<div class="custom-alert alert-${type === 'success' ? 'success' : 'danger'}">
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    </div>`;

    $('#flash-container').append(alertHtml);

    setTimeout(() => {
        $('.custom-alert').fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000);
}


    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            let query = $(this).val();
            let suggestionBox = $('#suggestion-box');
            let suggestionList = $('#suggestion-list');

            if (query.length >= 2) {
                $.ajax({
                    url: "{{ route('product.search.suggestions') }}",
                    method: "GET",
                    data: {
                        q: query
                    },
                    success: function(data) {
                        suggestionList.empty();
                        if (data.length > 0) {
                            data.forEach(product => {
                                // પ્રોડક્ટની લિંક તૈયાર કરો
                                let detailUrl = "{{ url('/product') }}/" + product
                                    .slug;
                                let imageUrl = "{{ asset('storage') }}/" + product
                                    .imgs;

                                suggestionList.append(`
                                <li class="list-group-item list-group-item-action d-flex align-items-center" style="cursor: pointer; padding: 10px;"
                                    onclick="window.location.href='${detailUrl}'">
                                    <img src="${imageUrl}" style="width: 40px; height: 40px; margin-right: 15px; border-radius: 4px;">
                                    <div>
                                        <div style="font-weight: 600; font-size: 14px; color: #333;">${product.name}</div>
                                        <div style="font-size: 12px; color: #28a745;">₹ ${product.dp}</div>
                                    </div>
                                </li>
                                `);
                            });
                            suggestionBox.show();
                        } else {
                            suggestionBox.hide();
                        }
                    }
                });
            } else {
                suggestionBox.hide();
            }
        });

        // બોક્સની બહાર ક્લિક કરવાથી લિસ્ટ છુપાવો
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.header-form').length) {
                $('#suggestion-box').hide();
            }
        });
    });



    function openQuickView(productData) {
        // જો ડેટા સ્ટ્રીંગ તરીકે આવે તો તેને ઓબ્જેક્ટમાં ફેરવો
        let product = typeof productData === 'string' ? JSON.parse(productData) : productData;

        const storagePath = "{{ asset('storage') }}/";
        const detailUrl = "{{ url('/product') }}/" + product.slug;
        const currency = "{{ company_info('currancy', '₹') }}";

        let mrp = parseFloat(product.mrp);
        let dp = parseFloat(product.dp);
        let badge = $('#modal-discount-badge');

        if (mrp > dp && mrp > 0) {
            // સૂત્ર: ((MRP - DP) / MRP) * 100
            let discountPercent = Math.round(((mrp - dp) / mrp) * 100);

            // ૨. વેલ્યુ લેબલમાં સેટ કરો અને તેને બતાવો
            badge.text('-' + discountPercent + '%').show();
        } else {
            // જો MRP ઓછી હોય તો બેજ છુપાવો
            badge.hide();
        }

        // ટેક્સ્ટ ડેટા સેટ કરો
        $('#modal-product-id').val(product.id);
        $('#modal-product-link').attr('href', detailUrl).text(product.name);
        $('#modal-sku').text(product.p_code || 'N/A');
        $('#modal-desc').text(product.p_dese || '');
        $('#modal-mrp').text(currency + product.mrp);
        $('#modal-dp').html(currency + product.dp + '<small>/per piece</small>');
        $('#modal-bv').text(product.product_bv || 0);
        $('#modal-pv').text(product.pv || 0);
        $('#modal-qty').val(1);

        // મેઈન ઈમેજ સેટ કરો
        $('#modal-main-img').attr('src', storagePath + product.imgs);

        // ગેલેરી થમ્બનેલ્સ
        let thumbsHtml =
            `<li><img src="${storagePath + product.imgs}" class="img-thumbnail"
        style="width:60px; height:60px; cursor:pointer; object-fit:cover;"
        onclick="updateMainImg('${storagePath + product.imgs}')"></li>`;

        if (product.more_imgs) {
            let gallery = (typeof product.more_imgs === 'string') ? JSON.parse(product.more_imgs) : product.more_imgs;
            gallery.forEach(img => {
                thumbsHtml +=
                    `<li><img src="${storagePath + img}" class="img-thumbnail"
        style="width:60px; height:60px; cursor:pointer; object-fit:cover;"
        onclick="updateMainImg('${storagePath + img}')"></li>`;
            });
        }
        $('#modal-gallery-thumbs').html(thumbsHtml);

        // મોડલ ઓપન કરવાની સાચી રીત (Bootstrap 5)
        var myModal = new bootstrap.Modal(document.getElementById('product-view'));
        myModal.show();
    }
    // સબ-ઈમેજ પર ક્લિક કરવાથી મેઈન ઈમેજ બદલાશે
    function updateMainImg(url) {
        $('#modal-main-img').attr('src', url);
    }

    // ક્વોન્ટિટી વધારવા-ઘટાડવા માટે
    function updateQty(val) {
        let input = $('#modal-qty');
        let currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            let newVal = currentVal + val;
            if (newVal >= 1) input.val(newVal);
        }
    }
</script>




<!-- CUSTOM -->
<script src="{{ asset('main/js/nice-select.js') }}"></script>
<script src="{{ asset('main/js/countdown.js') }}"></script>
<script src="{{ asset('main/js/accordion.js') }}"></script>
<script src="{{ asset('main/js/venobox.js') }}"></script>
<script src="{{ asset('main/js/slick.js') }}"></script>
<script src="{{ asset('main/js/main.js') }}"></script>
<!--=====================================
            JS LINK PART END
=======================================-->



