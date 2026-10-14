{{--
    Read-only summary of the member's full profile, shown on My Profile.

    Expects: $user, and optionally $kyc (App\Models\KycDetail) and $sponsor.

    KYC-owned values (PAN, Aadhaar, bank) are read from $kyc rather
    than the user record, so there is a single source for them.
--}}
@php
    $dash = '<span class="text-muted">—</span>';

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

    $sections = [
        'Personal Details' => [
            'Title' => $user->title,
            'First Name' => $user->first_name,
            'Middle Name' => $user->middle_name,
            'Last Name' => $user->last_name,
            'Unique Name' => $user->unique_name,
            'Gender' => $user->gender,
            'Date of Birth' => $fmtDate($user->date_of_birth),
            'Marriage Anniversary' => $fmtDate($user->marriage_anniversary),
            'Profession' => $user->profession,
            'Nationality' => $user->nationality,
        ],
        'Contact' => [
            'Email ID' => $user->email,
            'Mobile' => $user->mobile,
            'Alternate Mobile' => $user->alternate_mobile,
            'Landline' => $landline !== '' && $landline !== '-' ? $landline : null,
        ],
        'Address' => [
            'Country' => $user->country,
            'State' => $user->state,
            'District' => $user->district,
            'City' => $user->city,
            'Pin Code' => $user->pin_code,
            'Address' => $user->address,
        ],
        'Nominee Information' => [
            'Nominee Name' => $nomineeName !== '' ? $nomineeName : null,
            'Gender' => $user->nominee_gender,
            'Date of Birth' => $fmtDate($user->nominee_dob),
            'Relation with Applicant' => $user->nominee_relation,
            'Email ID' => $user->nominee_email,
            'Mobile' => $user->nominee_mobile,
        ],
        'Account & Sponsor' => [
            'Distributor ID' => $user->username,
            'Sponsor Name' => $sponsor->name ?? null,
            'Sponsor ID' => $user->sponsor_id,
            'Sponsor Mobile' => $sponsor->mobile ?? null,
            'Date of Joining' => $fmtDate($user->join_date),
            'Position' => match ((int) $user->position) { 1 => 'Left', 2 => 'Right', default => null },
        ],
    ];

    // Only shown once KYC exists; these are never editable from this page.
    if ($kyc ?? null) {
        $sections['KYC & Bank Details'] = [
            'PAN No.' => $kyc->pan_no,
            'Aadhaar No.' => $kyc->tax_id,
            'Bank Name' => $kyc->bank_name,
            'Bank A/c No.' => $kyc->account_no,
            'A/C Holder Name' => $kyc->account_holder_name,
            'IFSC' => $kyc->ifsc_code,
            'Branch Name' => $kyc->bank_branch,
        ];
    }
@endphp

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <h5 class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span><i class="bx bx-detail me-1"></i> Profile Details</span>
                <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit Profile
                </a>
            </h5>

            <div class="card-body">
                @if (!($kyc ?? null))
                    <div class="alert alert-warning py-2">
                        Your KYC is not submitted yet, so PAN, Aadhaar and bank details are not shown.
                        <a href="{{ route('user.kyc.show') }}" class="alert-link">Complete KYC</a>
                    </div>
                @endif

                <div class="row g-4">
                    @foreach ($sections as $sectionTitle => $rows)
                        <div class="col-lg-6">
                            <div class="border rounded h-100">
                                <div class="border-bottom px-3 py-2 fw-medium">{{ $sectionTitle }}</div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            @foreach ($rows as $label => $value)
                                                <tr>
                                                    <td class="text-muted" style="width:45%">{{ $label }}</td>
                                                    <td class="fw-medium" style="overflow-wrap:anywhere">
                                                        {!! ($value === null || $value === '') ? $dash : e($value) !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
