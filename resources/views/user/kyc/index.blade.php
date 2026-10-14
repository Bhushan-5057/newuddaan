@extends('user.layouts.app')

@section('title', 'KYC Details')

@section('content')

<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <div class="container my-4">
        <!-- Breadcrumb -->
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">KYC</li>
            </ol>
        </nav> --}}

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (
            $kycDetails->kyc_status_identity === 'submitted' ||
                $kycDetails->kyc_status_pan === 'submitted' ||
                $kycDetails->kyc_status_bank === 'submitted')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Information!</strong> Your KYC request has been submitted. Please wait for an action from the admin.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="kycTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="status-tab" data-bs-toggle="tab" data-bs-target="#kyc-status"
                            type="button" role="tab" aria-controls="kyc-status" aria-selected="true">KYC
                            Status</button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-details"
                            type="button" role="tab" aria-controls="personal-details" aria-selected="false">Personal
                            Details</button>
                    </li> --}}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="identity-tab" data-bs-toggle="tab"
                            data-bs-target="#identity-verification" type="button" role="tab"
                            aria-controls="identity-verification" aria-selected="false">Identity Verification</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pan-tab" data-bs-toggle="tab" data-bs-target="#pan-details"
                            type="button" role="tab" aria-controls="pan-details" aria-selected="false">Pan Card
                            Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank-details"
                            type="button" role="tab" aria-controls="bank-details" aria-selected="false">Bank
                            Details</button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content mt-4" id="kycTabsContent">
                    <!-- KYC Status Tab Pane -->
                    <div class="tab-pane fade show active" id="kyc-status" role="tabpanel" aria-labelledby="status-tab">
                        <h5 class="card-title mb-3">KYC Submission Status</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Identity</th>
                                        <th>Pan</th>
                                        <th>Bank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if ($kycDetails->kyc_status_identity == 'submitted')
                                                <span class="badge bg-primary">Submitted</span>
                                            @elseif($kycDetails->kyc_status_identity == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($kycDetails->kyc_status_identity == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kycDetails->kyc_status_pan == 'submitted')
                                                <span class="badge bg-primary">Submitted</span>
                                            @elseif($kycDetails->kyc_status_pan == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($kycDetails->kyc_status_pan == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kycDetails->kyc_status_bank == 'submitted')
                                                <span class="badge bg-primary">Submitted</span>
                                            @elseif($kycDetails->kyc_status_bank == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($kycDetails->kyc_status_bank == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Personal Details Tab Pane -->
                    <div class="tab-pane fade" id="personal-details" role="tabpanel" aria-labelledby="personal-tab">
                        <h5 class="card-title mb-3">Personal Details</h5>
                        @if ($kycDetails->kyc_status_personal == 'rejected')
                            <div class="alert alert-danger" role="alert">
                                <strong>KYC Rejected!</strong> Reason: {{ $kycDetails->personal_remark }}
                            </div>
                        @endif
                        <form action="{{ route('user.kyc.personal.submit') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name', $kycDetails->name) }}" placeholder="Name"
                                        @if ($kycDetails->kyc_status_personal == 'submitted' || $kycDetails->kyc_status_personal == 'approved') readonly @endif>
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ old('email', $kycDetails->email) }}" placeholder="Email"
                                        @if ($kycDetails->kyc_status_personal == 'submitted' || $kycDetails->kyc_status_personal == 'approved') readonly @endif>
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="tel" id="mobile" name="mobile" class="form-control"
                                        value="{{ old('mobile', $kycDetails->mobile) }}" placeholder="Mobile"
                                        @if ($kycDetails->kyc_status_personal == 'submitted' || $kycDetails->kyc_status_personal == 'approved') readonly @endif>
                                    @error('mobile')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile_pic" class="form-label">Profile Pic</label>
                                    <input type="file" id="profile_pic" name="profile_pic" class="form-control"
                                        accept="image/*" @if ($kycDetails->kyc_status_personal == 'submitted' || $kycDetails->kyc_status_personal == 'approved') disabled @endif>
                                    @error('profile_pic')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @if ($kycDetails->profile_pic)
                                        <img src="{{ asset('storage/' . $kycDetails->profile_pic) }}"
                                            class="img-thumbnail mt-2" style="max-width: 150px;" alt="Profile Picture">
                                    @endif
                                </div>
                                <div class="col-12">
                                    @if ($kycDetails->kyc_status_personal !== 'approved' && $kycDetails->kyc_status_personal !== 'submitted')
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Identity Verification Tab Pane -->
                    <div class="tab-pane fade" id="identity-verification" role="tabpanel"
                        aria-labelledby="identity-tab">
                        <h5 class="card-title mb-3">Identity Verification</h5>
                        @if ($kycDetails->kyc_status_identity == 'rejected')
                            <div class="alert alert-danger" role="alert">
                                <strong>KYC Rejected!</strong> Reason: {{ $kycDetails->identity_remark }}
                            </div>
                        @endif
                        <form action="{{ route('user.kyc.identity.submit') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="document_type" class="form-label">Select Document</label>
                                    <select id="document_type" name="attached_doc" class="form-select"
                                        @if ($kycDetails->kyc_status_identity == 'submitted' || $kycDetails->kyc_status_identity == 'approved') disabled @endif>
                                       
                                       <option value="aadhaar" @if ($kycDetails->attached_doc == 'aadhaar') selected @endif>Aadhaar
                                        </option>
                                        {{-- <option value="voter" @if ($kycDetails->attached_doc == 'voter') selected @endif>Voter ID
                                        </option> 
                                        <option value="driving" @if ($kycDetails->attached_doc == 'driving') selected @endif>RC Book
                                            </option> --}}
                                        {{-- <option value="passport" @if ($kycDetails->attached_doc == 'passport') selected @endif>
                                            Passport</option> --}}
                                    </select>
                                    @error('attached_doc')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="tax_id" class="form-label">Document No</label>
                                    <input type="text" id="tax_id" name="tax_id" class="form-control"
                                        value="{{ old('tax_id', $kycDetails->tax_id) }}" placeholder="Document No"
                                        @if ($kycDetails->kyc_status_identity == 'submitted' || $kycDetails->kyc_status_identity == 'approved') readonly @endif>
                                    @error('tax_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="front_image" class="form-label">Front Image</label>
                                    <input type="file" id="front_image" name="front_image" class="form-control"
                                        accept="image/*" @if ($kycDetails->kyc_status_identity == 'submitted' || $kycDetails->kyc_status_identity == 'approved') disabled @endif required>
                                    @error('front_image')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @if ($kycDetails->front_image)
                                        <img src="{{ asset('storage/' . $kycDetails->front_image) }}"
                                            class="img-thumbnail mt-2" style="max-width: 150px;" alt="Front Image">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="back_image" class="form-label">Back Image</label>
                                    <input type="file" id="back_image" name="back_image" class="form-control"
                                        accept="image/*" @if ($kycDetails->kyc_status_identity == 'submitted' || $kycDetails->kyc_status_identity == 'approved') disabled @endif required>
                                    @error('back_image')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @if ($kycDetails->back_image)
                                        <img src="{{ asset('storage/' . $kycDetails->back_image) }}"
                                            class="img-thumbnail mt-2" style="max-width: 150px;" alt="Back Image">
                                    @endif
                                </div>
                                <div class="col-12">
                                    @if ($kycDetails->kyc_status_identity !== 'submitted' && $kycDetails->kyc_status_identity !== 'approved')
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Pan Card Details Tab Pane -->
                    <div class="tab-pane fade" id="pan-details" role="tabpanel" aria-labelledby="pan-tab">
                        <h5 class="card-title mb-3">Pan Card Details</h5>
                        @if ($kycDetails->kyc_status_pan == 'rejected')
                            <div class="alert alert-danger" role="alert">
                                <strong>KYC Rejected!</strong> Reason: {{ $kycDetails->pan_remark }}
                            </div>
                        @endif
                        <form action="{{ route('user.kyc.pan.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="pan_no" class="form-label">PAN Number</label>
                                    <input type="text" id="pan_no" name="pan_no" class="form-control"
                                        value="{{ old('pan_no', $kycDetails->pan_no) }}" placeholder="PAN Number"
                                        @if ($kycDetails->kyc_status_pan == 'submitted' || $kycDetails->kyc_status_pan == 'approved') readonly @endif>
                                    @error('pan_no')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="front_image_pan" class="form-label">PAN Card Front Image</label>
                                    <input type="file" id="front_image_pan" name="front_image_pan"
                                        class="form-control" accept="image/*"
                                        @if ($kycDetails->kyc_status_pan == 'submitted' || $kycDetails->kyc_status_pan == 'approved') disabled @endif>
                                    @error('front_image_pan')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @if ($kycDetails->front_image_pan)
                                        <img src="{{ asset('storage/' . $kycDetails->front_image_pan) }}"
                                            class="img-thumbnail mt-2" style="max-width: 150px;" alt="PAN Card Image">
                                    @endif
                                </div>
                                <div class="col-12">
                                    @if ($kycDetails->kyc_status_pan !== 'submitted' && $kycDetails->kyc_status_pan !== 'approved')
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bank Details Tab Pane -->
                    <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-tab">
                        <h5 class="card-title mb-3">Bank Details</h5>
                        @if ($kycDetails->kyc_status_bank == 'rejected')
                            <div class="alert alert-danger" role="alert">
                                <strong>KYC Rejected!</strong> Reason: {{ $kycDetails->bank_remark }}
                            </div>
                        @endif
                        <form action="{{ route('user.kyc.bank.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                    <input type="text" id="account_holder_name" name="account_holder_name"
                                        class="form-control"
                                        value="{{ old('account_holder_name', $kycDetails->account_holder_name) }}"
                                        placeholder="Account Holder Name"
                                        @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') readonly @endif>
                                    @error('account_holder_name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control"
                                        value="{{ old('bank_name', $kycDetails->bank_name) }}" placeholder="Bank Name"
                                        @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') readonly @endif>
                                    @error('bank_name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="account_no" class="form-label">Account Number</label>
                                    <input type="text" id="account_no" name="account_no" class="form-control"
                                        value="{{ old('account_no', $kycDetails->account_no) }}"
                                        placeholder="Account Number" @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') readonly @endif>
                                    @error('account_no')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                                    <input type="text" id="ifsc_code" name="ifsc_code" class="form-control"
                                        value="{{ old('ifsc_code', $kycDetails->ifsc_code) }}" placeholder="IFSC Code"
                                        @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') readonly @endif>
                                    @error('ifsc_code')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="bank_branch" class="form-label">Bank Branch</label>
                                    <input type="text" id="bank_branch" name="bank_branch" class="form-control"
                                        value="{{ old('bank_branch', $kycDetails->bank_branch) }}"
                                        placeholder="Bank Branch" @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') readonly @endif>
                                    @error('bank_branch')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="front_image_bank" class="form-label">Bank Passbook/Statement</label>
                                    <input type="file" id="front_image_bank" name="front_image_bank"
                                        class="form-control" accept="image/*"
                                        @if ($kycDetails->kyc_status_bank == 'submitted' || $kycDetails->kyc_status_bank == 'approved') disabled @endif>
                                    @error('front_image_bank')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @if ($kycDetails->front_image_bank)
                                        <img src="{{ asset('storage/' . $kycDetails->front_image_bank) }}"
                                            class="img-thumbnail mt-2" style="max-width: 150px;" alt="Bank Document">
                                    @endif
                                </div>
                                <div class="col-12">
                                    @if ($kycDetails->kyc_status_bank !== 'submitted' && $kycDetails->kyc_status_bank !== 'approved')
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Re-open the last active tab on page reload, useful for validation errors
            var activeTab = '{{ session('active_tab') }}';
            if (activeTab) {
                var triggerEl = document.querySelector('button[data-bs-target="#' + activeTab + '"]');
                if (triggerEl) {
                    var tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }
        });
    </script>

