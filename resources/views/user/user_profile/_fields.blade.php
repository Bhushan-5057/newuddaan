{{--
    Member-editable profile fields, shared by the edit form.

    Expects: $user, and optionally $kyc (App\Models\KycDetail) and $sponsor.

    Deliberately does NOT ask for PAN, Aadhaar, address proof or bank details -
    those belong to KYC and are shown here read-only, sourced from kyc_details,
    so a member never enters them twice.
--}}
@php
    $titles = ['Mr.', 'Mrs.', 'Ms.', 'Dr.'];
    $genders = ['Male', 'Female', 'Other'];

    // old() wins after a failed submit, otherwise the stored value.
    $val = function (string $field, $default = null) use ($user) {
        $current = $user->{$field} ?? $default;
        if ($current instanceof \Carbon\Carbon) {
            $current = $current->format('Y-m-d');
        }
        return old($field, $current);
    };
@endphp

<hr>
<h5 class="mt-2 mb-3"><i class="bx bx-user me-1"></i> Personal Details</h5>
<div class="row">
    <div class="mb-3 col-md-2">
        <label class="form-label" for="title">Title</label>
        <select id="title" name="title" class="form-select @error('title') is-invalid @enderror">
            <option value="">Select</option>
            @foreach ($titles as $t)
                <option value="{{ $t }}" @selected($val('title') === $t)>{{ $t }}</option>
            @endforeach
        </select>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name"
               class="form-control js-name-alpha @error('first_name') is-invalid @enderror"
               pattern="[A-Za-z]+" title="Letters only, no spaces or numbers"
               value="{{ $val('first_name') }}">
        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="middle_name">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name"
               class="form-control js-name-alpha @error('middle_name') is-invalid @enderror"
               pattern="[A-Za-z]+" title="Letters only, no spaces or numbers"
               value="{{ $val('middle_name') }}">
        @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name"
               class="form-control js-name-alpha @error('last_name') is-invalid @enderror"
               pattern="[A-Za-z]+" title="Letters only, no spaces or numbers"
               value="{{ $val('last_name') }}">
        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="gender">Gender</label>
        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
            <option value="">Select</option>
            @foreach ($genders as $g)
                <option value="{{ $g }}" @selected($val('gender') === $g)>{{ $g }}</option>
            @endforeach
        </select>
        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="date_of_birth">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth"
               class="form-control @error('date_of_birth') is-invalid @enderror"
               value="{{ $val('date_of_birth') }}" max="{{ now()->toDateString() }}">
        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="marriage_anniversary">Marriage Anniversary</label>
        <input type="date" id="marriage_anniversary" name="marriage_anniversary"
               class="form-control @error('marriage_anniversary') is-invalid @enderror"
               value="{{ $val('marriage_anniversary') }}" max="{{ now()->toDateString() }}">
        @error('marriage_anniversary')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="profession">Profession</label>
        <input type="text" id="profession" name="profession"
               class="form-control @error('profession') is-invalid @enderror"
               value="{{ $val('profession') }}" placeholder="e.g. Business, Service">
        @error('profession')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="nationality">Nationality</label>
        <input type="text" id="nationality" name="nationality"
               class="form-control @error('nationality') is-invalid @enderror"
               value="{{ $val('nationality', 'Indian') }}">
        @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h5 class="mt-2 mb-3"><i class="bx bx-phone me-1"></i> Contact</h5>
<div class="row">
    <div class="mb-3 col-md-4">
        <label class="form-label" for="mobile">Contact Number</label>
        <input type="text" id="mobile" name="mobile" inputmode="numeric"
               class="form-control js-indian-mobile @error('mobile') is-invalid @enderror"
               pattern="[6-9][0-9]{9}" maxlength="10" minlength="10"
               title="10-digit Indian mobile starting with 6, 7, 8 or 9"
               value="{{ old('mobile', $user->mobile) }}">
        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Used for login &amp; OTP. Enter a valid 10-digit number.</small>
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="alternate_mobile">Alternate Mobile</label>
        <input type="text" id="alternate_mobile" name="alternate_mobile" inputmode="numeric"
               class="form-control js-indian-mobile @error('alternate_mobile') is-invalid @enderror"
               pattern="[6-9][0-9]{9}" maxlength="10" minlength="10"
               title="10-digit Indian mobile starting with 6, 7, 8 or 9"
               value="{{ $val('alternate_mobile') }}">
        @error('alternate_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label">Landline</label>
        <div class="input-group">
            <input type="text" name="std_code" inputmode="numeric" style="max-width:110px"
                   class="form-control @error('std_code') is-invalid @enderror"
                   value="{{ $val('std_code') }}" placeholder="STD">
            <input type="text" name="phone_number" inputmode="numeric"
                   class="form-control js-digits-only @error('phone_number') is-invalid @enderror"
                   pattern="[0-9]{5,15}" maxlength="15"
                   value="{{ $val('phone_number') }}" placeholder="Phone Number">
        </div>
        @error('std_code')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h5 class="mt-2 mb-3"><i class="bx bx-map me-1"></i> Address</h5>
<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="address">Address</label>
        <textarea id="address" name="address" rows="2" maxlength="500"
                  class="form-control @error('address') is-invalid @enderror"
                  placeholder="House / Flat No., Street, Area, Landmark">{{ $val('address') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="country">Country</label>
        <input type="text" id="country" name="country"
               class="form-control @error('country') is-invalid @enderror"
               value="{{ $val('country', 'India') }}">
        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="state">State</label>
        <input type="text" id="state" name="state"
               class="form-control @error('state') is-invalid @enderror"
               value="{{ $val('state') }}">
        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="district">District</label>
        <input type="text" id="district" name="district"
               class="form-control @error('district') is-invalid @enderror"
               value="{{ $val('district') }}">
        @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="city">City</label>
        <input type="text" id="city" name="city"
               class="form-control @error('city') is-invalid @enderror"
               value="{{ $val('city') }}">
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="pin_code">Pin Code</label>
        <input type="text" id="pin_code" name="pin_code" inputmode="numeric" maxlength="10"
               class="form-control @error('pin_code') is-invalid @enderror"
               value="{{ $val('pin_code') }}">
        @error('pin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h5 class="mt-2 mb-3"><i class="bx bx-user-plus me-1"></i> Nominee Information</h5>
<div class="row">
    <div class="mb-3 col-md-2">
        <label class="form-label" for="nominee_title">Title</label>
        <select id="nominee_title" name="nominee_title" class="form-select">
            <option value="">Select</option>
            @foreach ($titles as $t)
                <option value="{{ $t }}" @selected($val('nominee_title') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="nominee_first_name">First Name</label>
        <input type="text" id="nominee_first_name" name="nominee_first_name"
               class="form-control js-name-alpha @error('nominee_first_name') is-invalid @enderror"
               pattern="[A-Za-z]+" title="Letters only, no spaces or numbers"
               value="{{ $val('nominee_first_name') }}">
        @error('nominee_first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="nominee_middle_name">Middle Name</label>
        <input type="text" id="nominee_middle_name" name="nominee_middle_name"
               class="form-control js-name-alpha" pattern="[A-Za-z]+"
               title="Letters only, no spaces or numbers"
               value="{{ $val('nominee_middle_name') }}">
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="nominee_last_name">Last Name</label>
        <input type="text" id="nominee_last_name" name="nominee_last_name"
               class="form-control js-name-alpha" pattern="[A-Za-z]+"
               title="Letters only, no spaces or numbers"
               value="{{ $val('nominee_last_name') }}">
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="nominee_gender">Gender</label>
        <select id="nominee_gender" name="nominee_gender" class="form-select @error('nominee_gender') is-invalid @enderror">
            <option value="">Select</option>
            @foreach ($genders as $g)
                <option value="{{ $g }}" @selected($val('nominee_gender') === $g)>{{ $g }}</option>
            @endforeach
        </select>
        @error('nominee_gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="nominee_dob">Nominee DOB</label>
        <input type="date" id="nominee_dob" name="nominee_dob"
               class="form-control @error('nominee_dob') is-invalid @enderror"
               value="{{ $val('nominee_dob') }}" max="{{ now()->toDateString() }}">
        @error('nominee_dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label" for="nominee_relation">Relation with Applicant</label>
        <input type="text" id="nominee_relation" name="nominee_relation"
               class="form-control js-name-words @error('nominee_relation') is-invalid @enderror"
               pattern="[A-Za-z]+( [A-Za-z]+)*" title="Letters only, no numbers"
               value="{{ $val('nominee_relation') }}" placeholder="e.g. Wife, Son, Father">
        @error('nominee_relation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="nominee_email">Email ID</label>
        <input type="email" id="nominee_email" name="nominee_email"
               class="form-control @error('nominee_email') is-invalid @enderror"
               value="{{ $val('nominee_email') }}">
        @error('nominee_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="nominee_mobile">Mobile</label>
        <input type="text" id="nominee_mobile" name="nominee_mobile" inputmode="numeric"
               class="form-control js-indian-mobile @error('nominee_mobile') is-invalid @enderror"
               pattern="[6-9][0-9]{9}" maxlength="10" minlength="10"
               title="10-digit Indian mobile starting with 6, 7, 8 or 9"
               value="{{ $val('nominee_mobile') }}">
        @error('nominee_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- ---------------------------------------------------------------------
     Read-only blocks. These are owned by KYC / the placement tree, so they
     are shown for reference and cannot be edited here.
--------------------------------------------------------------------- --}}
<hr>
<h5 class="mt-2 mb-3">
    <i class="bx bx-id-card me-1"></i> KYC Details
    <small class="text-muted fw-normal">&mdash; managed on the KYC page</small>
</h5>

@php
    // Only the sections the KYC page actually offers - see
    // KycDetail::ACTIVE_SECTIONS.
    $labels = ['identity' => 'Identity', 'pan' => 'PAN', 'bank' => 'Bank'];
    $kycSections = [];
    foreach (($kyc ? $kyc->sectionStatuses() : []) as $section => $state) {
        $kycSections[$labels[$section] ?? ucfirst($section)] = $state;
    }

    $kycBadge = [
        'approved'  => 'bg-success',
        'submitted' => 'bg-info',
        'pending'   => 'bg-warning text-dark',
        'rejected'  => 'bg-danger',
    ];

    $statuses = array_values(array_filter($kycSections));
    $allApproved = !empty($statuses) && count(array_filter($statuses, fn ($s) => $s === 'approved')) === count($statuses);
    $anyRejected = in_array('rejected', $statuses, true);

    // Anything not yet approved needs the member's attention.
    $needsAttention = !$kyc || !$allApproved;

    // Remarks the admin left, so the member knows what to fix.
    $kycRemarks = array_filter([
        'Identity' => $kyc->identity_remark ?? null,
        'PAN'      => $kyc->pan_remark ?? null,
        'Bank'     => $kyc->bank_remark ?? null,
    ]);
@endphp

@if ($needsAttention)
    <div class="alert {{ $anyRejected ? 'alert-danger' : 'alert-warning' }} d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
        <div>
            <i class="bx bx-error-circle me-1"></i>
            @if (!$kyc)
                Your KYC is not submitted yet. PAN, Aadhaar and bank details are required before withdrawals.
            @elseif ($anyRejected)
                Part of your KYC was <strong>rejected</strong>. Please correct it and submit again.
            @else
                Your KYC is <strong>not fully approved</strong> yet.
            @endif
        </div>
        <a href="{{ route('user.kyc.show') }}" class="btn btn-sm {{ $anyRejected ? 'btn-danger' : 'btn-warning' }}">
            <i class="bx bx-id-card me-1"></i>
            {{ $kyc ? 'Go to KYC page' : 'Complete KYC now' }}
        </a>
    </div>
@endif

@if ($kyc)
    {{-- Per-section status, so the member can see exactly what is outstanding --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach ($kycSections as $label => $state)
            <span class="badge {{ $kycBadge[$state] ?? 'bg-secondary' }}">
                {{ $label }}: {{ ucfirst($state ?? 'not started') }}
            </span>
        @endforeach
    </div>

    @if (!empty($kycRemarks))
        <div class="alert alert-secondary py-2 small">
            <strong>Admin remarks:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($kycRemarks as $label => $remark)
                    <li>{{ $label }}: {{ $remark }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $readonly = [
            'PAN No.' => $kyc->pan_no,
            'Aadhaar No.' => $kyc->tax_id,
            'Bank Name' => $kyc->bank_name,
            'Bank A/c No.' => $kyc->account_no,
            'A/C Holder Name' => $kyc->account_holder_name,
            'IFSC' => $kyc->ifsc_code,
            'Branch Name' => $kyc->bank_branch,
        ];
    @endphp
    <div class="row">
        @foreach ($readonly as $label => $value)
            <div class="mb-3 col-md-3">
                <label class="form-label">{{ $label }}</label>
                <input type="text" class="form-control" value="{{ $value ?: '—' }}" disabled readonly>
            </div>
        @endforeach
    </div>

    <a href="{{ route('user.kyc.show') }}" class="btn btn-sm btn-label-secondary mb-2">
        <i class="bx bx-edit-alt me-1"></i> Update KYC details
    </a>
@endif

<hr>
<h5 class="mt-2 mb-3">
    <i class="bx bx-user-voice me-1"></i> Sponsor Details
    <small class="text-muted fw-normal">&mdash; cannot be changed</small>
</h5>
<div class="row">
    <div class="mb-3 col-md-4">
        <label class="form-label">Sponsor Name</label>
        <input type="text" class="form-control"
               value="{{ $sponsor->name ?? ($user->sponsor_id ? $user->sponsor_id : 'Admin') }}" disabled readonly>
    </div>
    <div class="mb-3 col-md-4">
        <label class="form-label">Sponsor ID</label>
        <input type="text" class="form-control" value="{{ $user->sponsor_id ?: '—' }}" disabled readonly>
    </div>
    <div class="mb-3 col-md-4">
        <label class="form-label">Sponsor Mobile</label>
        <input type="text" class="form-control" value="{{ $sponsor->mobile ?? '—' }}" disabled readonly>
    </div>
    <div class="mb-3 col-md-4">
        <label class="form-label">Distributor ID</label>
        <input type="text" class="form-control" value="{{ $user->username }}" disabled readonly>
    </div>
    <div class="mb-3 col-md-4">
        <label class="form-label">Date of Joining</label>
        <input type="text" class="form-control"
               value="{{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d/m/Y') : '—' }}"
               disabled readonly>
    </div>
</div>
