               </div>
            </main>


        </div>
    </div>

    
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
                    <p class="footer-text f-w-600 mb-0">Copyright © 2026 {{ company_info('company_name') }}. All rights reserved</p>
                </div>
           
            </div>
        </div>
    </footer>
    <!-- Footer Section ends-->
</div>

<!-- modal -->

{{-- <div class="modal" data-bs-backdrop="static" id="welcomeCard" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content welcome-card">

            <div class="modal-body p-0">
                <div>
                    <img alt="img" class=" img-fluid" src="{{ asset('franchise/assets/images/modals/welcome-bg.png') }}" >
                    <div>
                        <img src="{{ asset('franchise/assets/images/modals/welcome-rocket.png') }}" class="modal-rocket img-fluid" alt="img">
                    </div>
                </div>
                <div class="text-end position-absolute end-0 top-0 p-3">
                    <i class="ti ti-x fs-5 text-dark f-w-600" data-bs-dismiss="modal"></i>
                </div>
                <div class="text-center position-relative welcome-card-content z-1 p-3">

                    <h2 class="f-w-600"> Welcome !👋 </h2>
                    <h6 class="f-s-15 text-dark f-w-500 mx-0 mx-sm-5"> Start Multipurpose, clean modern responsive bootstrap 5 admin template </h6>
                    <ul class="modal-features-list">
                        <li class="btn btn-light-primary flex-fill">Fully Responsive</li>
                        <li class="btn btn-light-primary flex-fill">Built with bootstrap 5</li>
                        <li class="btn btn-light-primary flex-fill">scss support</li>
                        <li class="btn btn-light-primary flex-fill">Light & Dark Mode</li>
                        <li class="btn btn-light-primary flex-fill">Enjoy Started!</li>
                    </ul>
                    <div class="mt-3 mb-4">
                        <button class="btn btn-primary text-white btn-lg" data-bs-dismiss="modal" type="button">
                            Get Started <i class="ti ti-chevrons-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> --}}

<!-- modal -->

<!--customizer-->
{{-- <div id="customizer"></div> --}}

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
<script src="{{ asset('franchise/assets/js/jquery-3.6.3.min.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('franchise/assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- Simple bar js-->
<script src="{{ asset('franchise/assets/vendor/simplebar/simplebar.js') }}"></script>

<!-- App js-->
<script src="{{ asset('franchise/assets/js/script.js') }}"></script>




<!-- phosphor js -->
<script src="{{ asset('franchise/assets/vendor/phosphor/phosphor.js') }}"></script>

<!-- Glight js -->
<script src="{{ asset('franchise/assets/vendor/glightbox/glightbox.min.js') }}"></script>

<!-- apexcharts-->
<script src="{{ asset('franchise/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

<!-- Customizer js-->
<script src="{{ asset('franchise/assets/js/customizer.js') }}"></script>

<!-- Ecommerce js-->
<script src="{{ asset('franchise/assets/js/ecommerce_dashboard.js') }}"></script>







<!-- Custom JS (check path) -->

  <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

         <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>

        

<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





{{-- <script>

    @if (session('success'))
 
        Swal.fire({

            toast: true,

            position: 'top-end',

            icon: 'success',

            title: '{{ session('success') }}',

            showConfirmButton: false,

            timer: 3000,

            timerProgressBar: true

        });

    @endif


    @if ($errors->any())

        Swal.fire({

            icon: 'error',

            title: 'Oops...',

            html: `{!! implode('<br>', $errors->all()) !!}`,

            confirmButtonColor: '#d33'

        });

    @endif

</script> --}}

    <form id="logoutForm" method="POST" action="{{ route('franchise.logout') }}" style="display:none;">@csrf</form>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Logout'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('logoutForm').submit(); }
            });
        }
    </script>

    {{-- SweetAlert Notifications (Success/Error) --}}
    @if(session('success') || session('error'))
    <script>
        Swal.fire({
            icon: '{{ session('success') ? "success" : "error" }}',
            title: '{{ session('success') ? "Success" : "Error" }}',
            text: {!! json_encode(session('success') ?? session('error')) !!},
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif



</body>


</html>