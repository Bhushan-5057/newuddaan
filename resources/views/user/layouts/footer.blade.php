<!-- Body main section ends -->



<!-- tap on top -->
<div class="go-top">
    <span class="progress-value">
        <i class="ti ti-chevron-up"></i>
    </span>
</div>

<!-- Footer Section starts-->
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-12">
                <p class="footer-text f-w-600 mb-0">Copyright © 2026 {{ company_info('company_name') }}. All rights
                    reserved</p>
            </div>
            <div class="col-md-3">
                <div class="footer-text text-end">
                    <a class="f-w-500 text-primary" > Need Help <i
                            class="ti ti-help"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section ends-->
</div>

<!-- modal -->



<!-- modal -->

<!--customizer-->
<div id="customizer"></div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // 1️⃣ Page load par badha collapse band kari do
        document.querySelectorAll('.collapse').forEach(function (el) {
            el.classList.remove('show');
        });

        // 2️⃣ Menu click par sirf ek j collapse open rahe
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (toggle) {

            toggle.addEventListener('click', function () {

                let targetSelector = this.getAttribute('href');
                let target = document.querySelector(targetSelector);

                // Baki badha collapse close karo
                document.querySelectorAll('.collapse.show').forEach(function (openCollapse) {
                    if (openCollapse !== target) {
                        let bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    }
                });
            });

        });

    });
</script>


<!-- latest jquery-->
<script src="{{ asset('user/assets/js/jquery-3.6.3.min.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('user/assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- Simple bar js-->
<script src="{{ asset('user/assets/vendor/simplebar/simplebar.js') }}"></script>

<!-- App js-->
<script src="{{ asset('user/assets/js/script.js') }}"></script>




<!-- phosphor js -->
<script src="{{ asset('user/assets/vendor/phosphor/phosphor.js') }}"></script>

<!-- Glight js -->
<script src="{{ asset('user/assets/vendor/glightbox/glightbox.min.js') }}"></script>

<!-- apexcharts-->
<script src="{{ asset('user/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

<!-- Customizer js-->
<script src="{{ asset('user/assets/js/customizer.js') }}"></script>

<!-- Ecommerce js-->
<script src="{{ asset('user/assets/js/ecommerce_dashboard.js') }}"></script>







<!-- Custom JS (check path) -->

<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>



<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




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
            '.details-add-group, .product-action-group, .view-add-group, .feature-content, .product-content, .product-details-btn'
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
<!-- Force Dark Theme - Keep it dark always -->
<script>
    (function() {
        // Ensure dark theme is applied
        document.body.classList.add('dark');
        document.body.classList.remove('light');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme-mode', 'dark');
    })();
</script>

</body>


</html>