<!DOCTYPE html>



<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">



<head>

    <meta charset="utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />



    <title>{{ company_info('title') }} Register</title>



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

    <link rel="stylesheet"
        href="../../assets/vendor/libs/@form-validation/umd/styles/index.min.css" />



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

    <style>
        .iti--allow-dropdown input,
        .iti--allow-dropdown input[type=text],
        .iti--separate-dial-code input,
        .iti--separate-dial-code input[type=tel],
        .iti--separate-dial-code input[type=text] {
            padding-right: 100px !important;
        }
    </style>



    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">

            <div class="authentication-inner py-4">

                <!-- Register Card -->

                <div class="card">

                    <div class="card-body">

                        <!-- Logo -->

                        <div class="app-brand justify-content-center">

                            <a href="#" class="app-brand-link gap-2">

                                <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo"
                                    style="width: {{ company_info('logo_width', '40px') }};"
                                    height="{{ company_info('logo_height', '40px') }}">

                            </a>

                        </div>

                        <!-- /Logo -->

                        <!-- <h4 class="mb-2">Adventure starts here 🚀</h4>

                        <p class="mb-4">Make your app management easy and fun!</p> -->







                        <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST">
                            @csrf

                            {{-- ✅ Sponsor --}}
                            @php
                            $isSponsorRequired = \App\Models\AdvanceInfo::where('label', 'is_sponsor_required')->value('value') ?? 'no';
                            $isSponsorRequired = strtolower($isSponsorRequired) === 'yes';
                            @endphp

                            <div class="mb-3">
                                <label for="sponsor_id">Sponsor ID{{ $isSponsorRequired ? ' *' : '' }}</label>
                                <input
                                    type="text"
                                    id="sponsor_id"
                                    name="sponsor_display"
                                    class="form-control @error('sponsor_display') is-invalid @enderror"
                                    value="{{ old('sponsor_display', $referrer) }}"
                                    {{ $isSponsorRequired ? 'required' : '' }}
                                    autocomplete="off"
                                    @if ($referrer) readonly @endif>
                                <div id="sponsor-name" class="form-text mt-1"></div>

                                @error('sponsor_display')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            {{-- ✅ Parent Username --}}
                            @if ($RegistrationType == 'binary' && $refParent)
                            <div class="mb-3">
                                <label for="parent" class="form-label">Parent Username</label>

                                {{-- Fixed parent from referral link --}}
                                <input type="hidden" name="parent" value="{{ $refParent }}">
                                <input type="text" class="form-control" value="{{ $refParent }}" readonly>

                                @error('parent')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif



                            {{-- ✅ Position (Side) --}}
                            @if ($RegistrationType == 'binary')
                            <div class="mb-3">
                                <label for="position">Position *</label>

                                @if ($refSide)
                                {{-- Fixed side from referral link --}}
                                <input type="hidden" name="side" value="{{ $refSide }}">
                                <input type="text" class="form-control" value="{{ ucfirst($refSide) }}" readonly>

                                @else
                                @if ($sponsorDefaultSide)
                                {{-- Sponsor's default side (left/right/auto) --}}
                                <input type="hidden" name="side" value="{{ $sponsorDefaultSide }}">
                                <input type="text" class="form-control" value="{{ ucfirst($sponsorDefaultSide) }}" readonly>
                                @else
                                {{-- Manual selection if sponsor has no default --}}
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="side" id="sideLeft" value="left"
                                            {{ old('side') == 'left' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="sideLeft">Left</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="side" id="sideRight" value="right"
                                            {{ old('side') == 'right' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="sideRight">Right</label>
                                    </div>
                                </div>
                                @endif
                                @endif

                                @error('side')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif




                            <div class="mb-3">

                                <label for="email" class="form-label">Email</label>

                                <input type="text" class="form-control" id="email" name="email"

                                    placeholder="Enter your email" value="{{ old('email') }}" />

                                @error('email')

                                <small class="text-danger">{{ $message }}</small>

                                @enderror

                            </div>@php

                            // This assumes you pass $passGenType from controller (e.g., 'manual' or 'auto')

                            $passGenType = $passGenType ?? 'manual'; // fallback default

                            @endphp



                            @if ($passGenType === 'manual')

                            <div class="mb-3 form-password-toggle">

                                <label class="form-label" for="password">Password</label>

                                <div class="input-group input-group-merge">

                                    <input type="password" id="password" class="form-control" name="password"

                                        placeholder="Password" aria-describedby="password" />


                                    <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>


                                @error('password')

                                <small class="text-danger">{{ $message }}</small>

                                @enderror

                                </div>



                            </div>



                            <div class="mb-3 form-password-toggle">

                                <label class="form-label" for="password_confirmation">Confirm Password</label>

                                <div class="input-group input-group-merge">

                                    <input type="password" id="password_confirmation" class="form-control"

                                        name="password_confirmation" placeholder="Confirm Password"

                                        aria-describedby="password_confirmation" />

                                    <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>

                                @error('password_confirmation')

                                <small class="text-danger">{{ $message }}</small>

                                @enderror

                                </div>

                            </div>

                            @endif





                            <!-- <div class="mb-3">

                                <div class="form-check">

                                    <input class="form-check-input" type="checkbox" id="terms-conditions"

                                        name="terms" />

                                    <label class="form-check-label" for="terms-conditions">

                                        I agree to

                                        <a href="javascript:void(0);">privacy policy & terms</a>

                                    </label>

                                    @error('terms')

                                    <small class="text-danger">{{ $message }}</small>

                                    @enderror

                                </div>

                            </div> -->

                            @error('custom')

                            <small class="text-danger">{{ $message }}</small>

                            @enderror

                            @foreach ($customFields as $field)

                            @php

                            $inputName = "custom[{$field->id}]";

                            $isRequired = $field->required ? 'required' : '';

                            $options = $field->options ? explode(',', $field->options) : [];

                            $lowerName = strtolower($field->name);

                            @endphp







                            <div class="form-group mb-3">

                                <label>{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>



                                {{-- Special input for phone/whatsapp --}}

                                @php

                                $fieldName = strtolower($field->name);

                                $inputName = "custom[{$field->id}]";

                                $isRequired = $field->required ? 'required' : '';

                                $isPhoneField = str_contains($fieldName, 'phone') || str_contains($fieldName, 'mobile') || str_contains($fieldName, 'whatsapp');

                                $showCountryCode = $isPhoneField && $countrycode == 'yes';

                                @endphp



                                @if ($isPhoneField)

                                <div class="input-group">

                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="mobile-{{ $field->id }}" name="{{ $inputName }}"

                                        class="form-control authnumber" placeholder="Enter Number" {{ $isRequired }}>







                                </div>



                                @elseif ($field->name === 'Adhar Card')

                                <div class="form-group">

                                    <input

                                        type="text"

                                        name="custom[{{ $field->id }}]"

                                        id="adharcard_number"

                                        class="form-control"

                                        required

                                        maxlength="19"

                                        pattern="(\d{4}\s?){4}"

                                        placeholder="XXXX XXXX XXXX XXXX">

                                </div>

                                @elseif ($field->name === 'Pan Card')

                                <div class="form-group">

                                    <input

                                        type="text"

                                        name="custom[{{ $field->id }}]"

                                        id="pan_number"

                                        class="form-control"

                                        required

                                        maxlength="10"

                                        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"

                                        placeholder="ABCDE1234F"

                                        style="text-transform: uppercase;">

                                </div>





                                {{-- Select --}}

                                @elseif (in_array($field->type, ['text', 'email', 'number', 'date']))

                                <input type="{{ $field->type }}" name="{{ $inputName }}" class="form-control"

                                    {{ $isRequired }}>



                                {{-- Select --}}

                                @elseif ($field->type === 'select')

                                <select name="{{ $inputName }}" class="form-control" {{ $isRequired }}>

                                    <option value="">Select</option>

                                    @foreach ($options as $option)

                                    <option value="{{ trim($option) }}">{{ trim($option) }}</option> @endforeach

                                </select>



                                {{-- Radio --}}
@elseif ($field->type === 'radio')
@foreach ($options as $option)

                                <div class="form-check">

    <input class="form-check-input" type="radio" name="{{ $inputName }}" value="{{ trim($option) }}"
        {{ $isRequired }}>

    <label class="form-check-label">{{ trim($option) }}</label>

    </div>

    @endforeach



    {{-- Checkbox --}}
@elseif ($field->type === 'checkbox')
    @foreach ($options as $index => $option)

        <div class="form-check">

            <input class="form-check-input" type="checkbox" name="{{ $inputName }}[{{ $index }}]"
                value="{{ trim($option) }}">

            <label class="form-check-label">{{ trim($option) }}</label>

        </div>

    @endforeach

    @endif

    </div>

    @endforeach











    <button class="btn btn-primary d-grid w-100 mt-3" type="submit">Sign up</button>

    </form>





    <p class="text-center">

        <span>Already have an account?</span>

        <a href="{{ route('login') }}">

            <span>Sign in instead</span>

        </a>

    </p>





    </div>

    </div>

    <!-- Register Card -->

    </div>

    </div>

    </div>



    <!-- / Content -->



    <!-- Trigger Modal with JS -->


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







    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <link rel="stylesheet"

        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />



    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

    @if ($countrycode)

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const inputs = document.querySelectorAll("input[type='tel']");



            inputs.forEach(input => {

                const fieldId = input.id.split('-')[1];

                const countryCodeInput = document.getElementById(`country-code-${fieldId}`);



                const iti = window.intlTelInput(input, {

                    initialCountry: "in",

                    separateDialCode: true,

                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"

                });



                if (countryCodeInput) {

                    countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;



                    input.addEventListener("blur", function() {

                        countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;

                    });

                }

            });

        });
    </script> @endif

        <script>
            $('#sponsor_id').on('keyup', function() {
                let sponsorId = $(this).val();

                if (sponsorId.length > 2) {
                    $.ajax({
                        url: " {{ route('auth.getSponsorName') }}", type: "GET" , data: { sponsor_id: sponsorId },
        success: function(response) { if (response.success) { $('#sponsor-name').html(`<span class="text-success">
    Sponsor Name: $ {
        response.name
    } < /span>`);
        }
        else {
            $('#sponsor-name').html('<span class="text-danger">Sponsor not found</span>');
        }
        },
        error: function() {
        $('#sponsor-name').html('<span class="text-danger">Error checking sponsor</span>');
        }
        });
        }
        else {
            $('#sponsor-name').html('');
        }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const cardInput = document.getElementById('adharcard_number');

            if (cardInput) {

                cardInput.addEventListener('input', function(e) {

                    let value = this.value.replace(/\D/g, ''); // remove non-digits

                    let formatted = value.match(/.{1,4}/g); // group every 4 digits



                    if (formatted) {

                        this.value = formatted.join(' ');

                    } else {

                        this.value = value;

                    }

                });

            }

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const panInput = document.getElementById('pan_number');

            if (panInput) {

                panInput.addEventListener('input', function() {

                    this.value = this.value.toUpperCase(); // force uppercase

                });

            }

        });
    </script>









    </body>



</html>
