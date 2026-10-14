

</div>

</div>

</div>

	



	<!-- Bootstrap JS -->






<script src="{{ asset('userpanel/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/chartjs/js/Chart.min.js') }}"></script>

<script src="{{ asset('userpanel/assets/plugins/chartjs/js/Chart.extension.js') }}"></script>

<script src="{{ asset('userpanel/assets/js/index.js') }}"></script>



<!-- App JS -->

<script src="{{ asset('userpanel/assets/js/app.js') }}"></script>



<!-- Custom JS (check path) -->

<script src="{{ asset('userpanel/assets/css/js/script.js') }}"></script>



  <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

         <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>

        

<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





<script>

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

</script>



</body>







</html>