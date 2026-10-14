@extends('user.layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Account Settings /</span> Edit Profile
    </h4>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <!-- ============================================ -->
        <!-- PROFILE DETAILS CARD                         -->
        <!-- ============================================ -->
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">
                    <i class="bx bx-user me-1"></i> Profile Details
                </h5>
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                                alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />

                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="profile_image" class="account-file-input" hidden />
                                </label>
                                <button type="button" class="btn btn-label-secondary account-image-reset mb-4" onclick="resetImage()">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <p class="mb-0">Allowed JPG, GIF or PNG.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0" />

                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- Username UPDATE SECTION                   -->
                        <!-- ============================================ -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <h5 class="mt-2 mb-3">
                                    <i class="bx bx-user-pin me-1"></i> Update Username
                                </h5>
                                <p class="text-muted small">Your Username is used as your public identifier. Choose something memorable.</p>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Current Username</label>
                                <input type="text" class="form-control" value="{{ $user->unique_name }}" disabled readonly style="background-color: #173538;">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">New Username</label>
                                <input type="text" 
                                    id="unique_name" 
                                    name="unique_name" 
                                    class="form-control @error('unique_name') is-invalid @enderror" 
                                    placeholder="Enter new Username" 
                                    value="{{ old('unique_name') }}"
                                    autocomplete="off"
                                    minlength="2"
                                    maxlength="255">
                                <div id="uniqueNameFeedback" class="mt-1 passwordValidation"></div>
                                <small class="text-muted">Minimum 2 characters. Must be unique across all users.</small>
                                @error('unique_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- PERSONAL / CONTACT / ADDRESS / NOMINEE      -->
                        <!-- plus read-only KYC and sponsor blocks       -->
                        <!-- ============================================ -->
                        @include('user.user_profile._fields')

                        <!-- ============================================ -->
                        <!-- CUSTOM FIELDS SECTION                      -->
                        <!-- ============================================ -->
                        @if($customFields->isNotEmpty())
                        <hr>
                        <h5 class="mt-2 mb-3">
                            <i class="bx bx-cog me-1"></i> Additional Information
                        </h5>
                        <div class="row">
                            @foreach($customFields as $field)
                            @continue($field->id === 6) {{-- Skip privacy policy & terms --}}
                            @php
                            $inputName = "custom[{$field->id}]";
                            $value = old("custom.{$field->id}", $user->custom_fields[$field->id] ?? '');
                            $isRequired = $field->required ? 'required' : '';
                            $options = $field->options ? explode(',', $field->options) : [];
                            $lowerName = strtolower($field->name);
                            @endphp

                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ $field->name }}{{ $field->required ? ' <span class="text-danger">*</span>' : '' }}</label>

                                @if(in_array($field->type, ['text', 'email', 'number', 'date']))
                                
                                @if(str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') || str_contains($lowerName, 'whatsapp'))
                                    @php
                                        $phoneValue = is_array($value) ? $value : [];
                                    @endphp
                                    
                                    <div class="input-group">
                                        <input
                                            type="tel"
                                            id="mobile-{{ $field->id }}"
                                            class="form-control authnumber"
                                            name="custom[{{ $field->id }}][number]"
                                            value="{{ $phoneValue['number'] ?? '' }}"
                                            data-country="{{ $phoneValue['country_iso'] ?? 'in' }}"
                                            placeholder="Enter number"
                                            {{ $isRequired }}
                                        >
                                        <input type="hidden" name="custom[{{ $field->id }}][country_code]" id="country-code-{{ $field->id }}">
                                        <input type="hidden" name="custom[{{ $field->id }}][country_name]" id="country-name-{{ $field->id }}">
                                        <input type="hidden" name="custom[{{ $field->id }}][country_iso]" id="country-iso-{{ $field->id }}">
                                        <input type="hidden" name="custom[{{ $field->id }}][full_number]" id="full-number-{{ $field->id }}">
                                    </div>

                                @else
                                    <input type="{{ $field->type }}" name="{{ $inputName }}" value="{{ $value }}" class="form-control" {{ $isRequired }}>
                                @endif

                                @elseif($field->type === 'select')
                                <select name="{{ $inputName }}" class="form-control" {{ $isRequired }}>
                                    <option value="">Select</option>
                                    @foreach($options as $option)
                                    <option value="{{ trim($option) }}" {{ $value == trim($option) ? 'selected' : '' }}>
                                        {{ trim($option) }}
                                    </option>
                                    @endforeach
                                </select>

                                @elseif($field->type === 'radio')
                                @foreach($options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $inputName }}" value="{{ trim($option) }}" {{ $value == trim($option) ? 'checked' : '' }} {{ $isRequired }}>
                                    <label class="form-check-label">{{ trim($option) }}</label>
                                </div>
                                @endforeach

                                @elseif($field->type === 'checkbox')
                                @php
                                $checkedValues = is_array($value) ? $value : explode(',', $value);
                                @endphp
                                @foreach($options as $index => $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="{{ $inputName }}[{{ $index }}]" value="{{ trim($option) }}" {{ in_array(trim($option), $checkedValues ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ trim($option) }}</label>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update Profile
                            </button>
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-label-secondary">
                                <i class="bx bx-reset me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CHANGE PASSWORD CARD (SEPARATE)              -->
        <!-- ============================================ -->
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    <i class="bx bx-lock-alt me-1"></i> Change Password
                </h5>
                <div class="card-body">
                    <p class="text-muted small">Leave all fields blank to keep your current password</p>
                    
                    <form action="{{ route('user.profile.update.password') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" id="current_password" name="current_password" 
                                        class="form-control @error('current_password') is-invalid @enderror" 
                                        placeholder="Enter current password" />
                                    <span class="input-group-text toggle-password" style="cursor:pointer" 
                                        onclick="togglePasswordVisibility('current_password', this)">
                                        <i class="bx bx-show"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" id="new_password" name="new_password" 
                                        class="form-control @error('new_password') is-invalid @enderror" 
                                        placeholder="Enter new password" />
                                    <span class="input-group-text toggle-password" style="cursor:pointer" 
                                        onclick="togglePasswordVisibility('new_password', this)">
                                        <i class="bx bx-show"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Minimum 6 characters</small>
                                @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                        placeholder="Confirm new password" />
                                    <span class="input-group-text toggle-password" style="cursor:pointer" 
                                        onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                        <i class="bx bx-show"></i>
                                    </span>
                                </div>
                                @error('new_password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-warning me-2">
                                <i class="bx bx-key me-1"></i> Change Password
                            </button>
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-label-secondary">
                                <i class="bx bx-reset me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

<script>
    function resetImage() {
        const avatar = document.getElementById('uploadedAvatar');
        avatar.src = "{{ asset('assets/img/avatars/1.png') }}";
        document.getElementById('upload').value = null;
    }

    // Toggle password visibility function
    function togglePasswordVisibility(inputId, element) {
        const input = document.getElementById(inputId);
        const icon = element.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bx bx-hide';
        } else {
            input.type = 'password';
            icon.className = 'bx bx-show';
        }
    }

    // ==========================================================
    // Live AJAX: Username availability
    // ==========================================================
    let uniqueNameTimer = null;
    const uniqueNameInput = document.getElementById('unique_name');
    const feedbackDiv = document.getElementById('uniqueNameFeedback');

    if (uniqueNameInput) {
        const currentUniqueName = "{{ $user->unique_name }}";
        
        uniqueNameInput.addEventListener('keyup', function () {
            const val = this.value.trim();
            clearTimeout(uniqueNameTimer);
            
            if (val === '') {
                feedbackDiv.innerHTML = '';
                return;
            }
            
            if (val.length < 2) {
                feedbackDiv.innerHTML = '<span class="text-warning">Name must be at least 2 characters long.</span>';
                return;
            }

            // If the value is the same as current, show info message
            if (val === currentUniqueName) {
                feedbackDiv.innerHTML = '<span class="text-info">This is your current Username.</span>';
                return;
            }
            
            uniqueNameTimer = setTimeout(function () {
                $.ajax({
                    url: "{{ route('auth.checkUniqueName') }}",
                    type: "GET",
                    data: { unique_name: val },
                    success: function (res) {
                        feedbackDiv.innerHTML = 
                            `<span class="${res.available ? 'text-success' : 'text-danger'}">${res.message}</span>`;
                    },
                    error: function() {
                        feedbackDiv.innerHTML = '<span class="text-danger">Error checking availability</span>';
                    }
                });
            }, 350);
        });

        // Also check on focus if there's a value
        uniqueNameInput.addEventListener('focus', function () {
            if (this.value.trim()) {
                this.dispatchEvent(new Event('keyup'));
            }
        });
    }

    // ==========================================================
    // Password validation (real-time)
    // ==========================================================
    document.addEventListener("DOMContentLoaded", function () {
        const password = document.getElementById("new_password");
        const confirmPassword = document.getElementById("new_password_confirmation");
        
        if (password && confirmPassword) {
            function validatePassword() {
                const val = password.value;
                if (val.length === 0) {
                    password.classList.remove('is-valid', 'is-invalid');
                    return;
                }
                
                if (val.length < 6) {
                    password.classList.add('is-invalid');
                    password.classList.remove('is-valid');
                } else {
                    password.classList.remove('is-invalid');
                    password.classList.add('is-valid');
                }
            }
            
            function validateConfirm() {
                const val = confirmPassword.value;
                if (val.length === 0) {
                    confirmPassword.classList.remove('is-valid', 'is-invalid');
                    return;
                }
                
                if (val === password.value && val.length >= 6) {
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.classList.add('is-valid');
                } else {
                    confirmPassword.classList.add('is-invalid');
                    confirmPassword.classList.remove('is-valid');
                }
            }
            
            password.addEventListener('keyup', function() {
                validatePassword();
                if (confirmPassword.value.length > 0) {
                    validateConfirm();
                }
            });
            
            confirmPassword.addEventListener('keyup', validateConfirm);
        }
    });

    // ==========================================================
    // IntlTelInput initialization
    // ==========================================================
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll("input[type='tel']");

        inputs.forEach(input => {
            const fieldId = input.id.split('-')[1];

            const codeInput  = document.getElementById(`country-code-${fieldId}`);
            const nameInput  = document.getElementById(`country-name-${fieldId}`);
            const isoInput   = document.getElementById(`country-iso-${fieldId}`);
            const fullInput  = document.getElementById(`full-number-${fieldId}`);

            const initialCountry = input.dataset.country || 'in';

            const iti = window.intlTelInput(input, {
                initialCountry: initialCountry,
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            function updatePhoneData() {
                const data = iti.getSelectedCountryData();
                if (!data) return;

                const number = input.value.replace(/\D/g, '');

                if (codeInput) codeInput.value = '+' + data.dialCode;
                if (nameInput) nameInput.value = data.name;
                if (isoInput) isoInput.value = data.iso2;
                if (fullInput) fullInput.value = '+' + data.dialCode + number;
            }

            updatePhoneData();

            input.addEventListener("keyup", updatePhoneData);
            input.addEventListener("countrychange", updatePhoneData);
        });
    });
</script>

@endsection