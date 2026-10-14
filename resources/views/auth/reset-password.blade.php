<!DOCTYPE html>



<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-assets-path="../../assets/"

    data-template="vertical-menu-template" data-theme="theme-default">



<head>

    <meta charset=" utf-8" />

    <meta name="viewport"

        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />



    <title>{{ company_info('title') }} Login</title>



    <meta name="description" content="" />



    <!-- Favicon -->

	 <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/png" /> 



    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link

        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"

        rel="stylesheet" />



    <!-- Icons -->

    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />

    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />



    <!-- Core CSS -->

    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />

    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="../../assets/css/demo.css" />



    <!-- Vendors CSS -->

    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />

    <!-- Vendor -->

    <link rel="stylesheet" href="../../assets/vendor/libs/@form-validation/umd/styles/index.min.css" />



    <!-- Page CSS -->

    <!-- Page -->

    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />



    <!-- Helpers -->

    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->

    <script src="../../assets/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="../../assets/js/config.js"></script>

</head>



<body>

    <!-- Content -->



    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">

            <div class="authentication-inner py-4">

                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <h4>Reset Password</h4>
                
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                
                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="password" required>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                
                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                
                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </form>
                    </div>
                </div>
                          

                <!-- /Register -->

            </div>

        </div>

    </div>



    <!-- / Content -->



    <!-- Core JS -->

    <!-- build:js assets/vendor/js/core.js -->



    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="../../assets/vendor/libs/popper/popper.js"></script>

    <script src="../../assets/vendor/js/bootstrap.js"></script>

    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>

    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>

    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>



    <!-- endbuild -->



    <!-- Vendors JS -->

    <script src="../../assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>

    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>

    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>



    <!-- Main JS -->

    <script src="../../assets/js/main.js"></script>



    <!-- Page JS -->

    <script src="../../assets/js/pages-auth.js"></script>

</body>



</html>