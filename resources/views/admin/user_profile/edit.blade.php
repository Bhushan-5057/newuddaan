@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Account Settings /</span> Account
    </h4>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                                alt="user-avatar" class="d-block rounded" height="100" width="100"
                                id="uploadedAvatar" />

                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="profile_image" class="account-file-input"
                                        hidden />
                                </label>
                                <button type="button" class="btn btn-label-secondary account-image-reset mb-4"
                                    onclick="resetImage()">
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
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-3">Custom Fields</h6>
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
                                <label class="form-label">{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>

                                @if(in_array($field->type, ['text', 'email', 'number', 'date']))
                                @if(str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') ||
                                str_contains($lowerName, 'whatsapp'))
                                <div class="input-group">
                                    <input type="tel" id="mobile-{{ $field->id }}" name="{{ $inputName }}"
                                        class="form-control authnumber" value="{{ $value }}" placeholder="Enter number"
                                        {{ $isRequired }}>
                                    <input type="hidden" name="country_code[{{ $field->id }}]"
                                        id="country-code-{{ $field->id }}">
                                </div>
                                @else
                                <input type="{{ $field->type }}" name="{{ $inputName }}" value="{{ $value }}"
                                    class="form-control" {{ $isRequired }}>
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
                                    <input class="form-check-input" type="radio" name="{{ $inputName }}"
                                        value="{{ trim($option) }}" {{ $value == trim($option) ? 'checked' : '' }}
                                        {{ $isRequired }}>
                                    <label class="form-check-label">{{ trim($option) }}</label>
                                </div>
                                @endforeach

                                @elseif($field->type === 'checkbox')
                                @php
                                $checkedValues = is_array($value) ? $value : explode(',', $value);
                                @endphp
                                @foreach($options as $index => $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="{{ $inputName }}[{{ $index }}]" value="{{ trim($option) }}"
                                        {{ in_array(trim($option), $checkedValues ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ trim($option) }}</label>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection



@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js">
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

<script>
function resetImage() {
    const avatar = document.getElementById('uploadedAvatar');
    avatar.src = "{{ asset('assets/img/avatars/1.png') }}";
    document.getElementById('upload').value = null;
}

document.addEventListener("DOMContentLoaded", function() {
    const inputs = document.querySelectorAll("input[type='tel']");

    inputs.forEach(input => {
        const fieldId = input.id.split('-')[1];
        const countryCodeInput = document.getElementById(`country-code-${fieldId}`);

        const iti = window.intlTelInput(input, {
            initialCountry: "in", // 🇮🇳 Set default country to India
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        // Set default country code on load
        if (countryCodeInput) {
            countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
        }

        // Update country code on change
        input.addEventListener("blur", function() {
            if (countryCodeInput) {
                countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
            }
        });
    });
});
</script>

@endsection