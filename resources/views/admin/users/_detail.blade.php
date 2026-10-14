{{--
    User detail body, rendered into the "View User" modal on the admin user
    list. Deliberately layout-free: UserController@adminshow returns just this
    fragment over AJAX.

    Expects: $user, $customFields, $packageTotal, $placementParent
--}}
@php
    $dash = '<span class="text-muted">—</span>';

    $statusBadge = match ((int) $user->status) {
        1 => ['bg-success', 'Active'],
        0 => ['bg-secondary', 'Inactive'],
        2 => ['bg-danger', 'Blocked'],
        default => ['bg-dark', 'Unknown'],
    };

    $fmtDate = function ($value) {
        if (empty($value)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($value)->format('d M Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    $landline = trim(($user->std_code ? $user->std_code . '-' : '') . ($user->phone_number ?? ''));

    $nomineeName = trim(implode(' ', array_filter([
        $user->nominee_title,
        $user->nominee_first_name,
        $user->nominee_middle_name,
        $user->nominee_last_name,
    ])));

    $personal = [
        'Title' => $user->title,
        'First Name' => $user->first_name,
        'Middle Name' => $user->middle_name,
        'Last Name' => $user->last_name,
        'Display Name' => $user->name,
        'Unique Name' => $user->unique_name,
        'Gender' => $user->gender,
        'Date of Birth' => $fmtDate($user->date_of_birth),
        'Marriage Anniversary' => $fmtDate($user->marriage_anniversary),
        'Profession' => $user->profession,
        'Nationality' => $user->nationality,
    ];

    $contact = [
        'Email' => $user->email,
        'Mobile' => $user->mobile,
        'Alternate Mobile' => $user->alternate_mobile,
        'Landline' => ($landline !== '' && $landline !== '-') ? $landline : null,
        'Country' => $user->country,
        'State' => $user->state,
        'District' => $user->district,
        'City' => $user->city,
        'Pin Code' => $user->pin_code,
        'Address' => $user->address,
    ];

    $identity = [
        'PAN' => $user->pan,
        'Aadhaar' => $user->aadhaar,
    ];

    $nominee = [
        'Nominee Name' => $nomineeName !== '' ? $nomineeName : null,
        'Gender' => $user->nominee_gender,
        'Date of Birth' => $fmtDate($user->nominee_dob),
        'Relation' => $user->nominee_relation,
        'Email' => $user->nominee_email,
        'Mobile' => $user->nominee_mobile,
    ];

    $account = [
        'Username' => $user->username,
        'Role' => $user->role ? ucfirst($user->role) : null,
        'Sponsor ID' => $user->sponsor_id,
        'Placement Parent' => $placementParent
            ? $placementParent->name . ' (' . $placementParent->username . ')'
            : null,
        'Position' => match ((int) $user->position) {
            1 => 'Left',
            2 => 'Right',
            default => null,
        },
        'Join Date' => $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : null,
    ];

    if (Advance_info('register_wallets', 'no') === 'yes') {
        $account['Wallet Address'] = $user->wallet_address;
    }

    $sections = [
        'Personal Details' => $personal,
        'Contact Details' => $contact,
        'Identity Documents' => $identity,
        'Nominee Information' => $nominee,
        'Account & Placement' => $account,
    ];

    // Bank details live in KYC; show them here so an admin does not have to
    // open the KYC screen separately.
    if ($user->kycDetail) {
        $sections['Bank Details (from KYC)'] = [
            'Bank Name' => $user->kycDetail->bank_name,
            'Account No.' => $user->kycDetail->account_no,
            'A/C Holder Name' => $user->kycDetail->account_holder_name,
            'IFSC' => $user->kycDetail->ifsc_code,
            'Branch' => $user->kycDetail->bank_branch,
            'KYC Address' => $user->kycDetail->address,
        ];
    }
@endphp

{{-- Header strip --}}
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
    <div>
        <h5 class="mb-1">{{ $user->name ?: $user->username }}</h5>
        <span class="text-muted">{{ $user->username }}</span>
        <span class="badge {{ $statusBadge[0] }} ms-1">{{ $statusBadge[1] }}</span>
        @if ($user->is_free_id == 1)
            <span class="badge bg-primary ms-1">Compensation ID</span>
        @endif
    </div>
    <div class="text-end">
        <div class="text-muted small">Total Package</div>
        <h5 class="mb-0">{{ company_info('currency', '$') }} {{ number_format($packageTotal, 2) }}</h5>
    </div>
</div>

<div class="row g-3">
    @foreach ($sections as $sectionTitle => $rows)
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <h6 class="card-header border-bottom mb-0 py-2">{{ $sectionTitle }}</h6>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach ($rows as $label => $value)
                                    <tr>
                                        <td class="text-muted" style="width:45%">{{ $label }}</td>
                                        <td class="fw-medium">
                                            {!! ($value === null || $value === '') ? $dash : e($value) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Sponsor / joined --}}
    <div class="col-md-6">
        <div class="card h-100 shadow-none border">
            <h6 class="card-header border-bottom mb-0 py-2">Sponsor</h6>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:45%">Sponsor</td>
                            <td class="fw-medium">
                                @if ($user->sponsor)
                                    {{ $user->sponsor->name }}
                                    <span class="text-muted">({{ $user->sponsor->username }})</span>
                                @else
                                    <span class="text-muted">Admin</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Registered</td>
                            <td class="fw-medium">{{ $user->created_at?->format('d M Y, H:i') ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KYC --}}
    <div class="col-md-6">
        <div class="card h-100 shadow-none border">
            <h6 class="card-header border-bottom mb-0 py-2">KYC Status</h6>
            <div class="card-body p-0">
                @if (!$user->kycDetail)
                    <p class="text-muted mb-0 p-3">This user has not started KYC.</p>
                @else
                    @php
                        $kyc = $user->kycDetail;
                        // Active sections only - see KycDetail::ACTIVE_SECTIONS.
                        $labels = ['identity' => 'Identity', 'pan' => 'PAN', 'bank' => 'Bank'];
                        $kycRows = [];
                        foreach ($kyc->sectionStatuses() as $section => $state) {
                            $kycRows[$labels[$section] ?? ucfirst($section)] = $state;
                        }
                        $kycBadge = [
                            'approved' => 'bg-success',
                            'submitted' => 'bg-info',
                            'pending' => 'bg-warning text-dark',
                            'rejected' => 'bg-danger',
                        ];
                    @endphp
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach ($kycRows as $label => $state)
                                <tr>
                                    <td class="text-muted" style="width:45%">{{ $label }}</td>
                                    <td>
                                        <span class="badge {{ $kycBadge[$state] ?? 'bg-secondary' }}">
                                            {{ ucfirst($state ?? 'unknown') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- Registration custom fields, when any are defined --}}
    @if ($customFields->isNotEmpty())
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <h6 class="card-header border-bottom mb-0 py-2">Registration Custom Fields</h6>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @php $stored = $user->custom_fields ?? []; @endphp
                                @foreach ($customFields as $field)
                                    @php
                                        $value = $stored[$field->id] ?? null;
                                        if (is_array($value)) {
                                            $value = implode(', ', $value);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-muted" style="width:45%">{{ $field->name }}</td>
                                        <td class="fw-medium">
                                            {!! ($value === null || $value === '') ? $dash : e($value) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
