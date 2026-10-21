@extends('admin.layouts.app')

@section('title', 'KYC Verification Requests')

@section('content')

<style>
.zoom-image {
    transition: transform 0.3s ease-in-out;
    cursor: zoom-in;
}
.zoom-image:hover {
    transform: scale(1.6);
    z-index: 9999;
    position: relative;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2>KYC Verification Management</h2>
        
        <!-- Navigation Tabs: સ્ટેટસ બદલવા માટે -->
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-primary {{ Route::is('admin.kyc.index') ? 'active' : '' }}">Pending Requests</a>
            <a href="{{ route('admin.kyc.approvedindex') }}" class="btn btn-outline-success {{ Route::is('admin.kyc.approvedindex') ? 'active' : '' }}">Approved KYC</a>
            <a href="{{ route('admin.kyc.rejectindex') }}" class="btn btn-outline-danger {{ Route::is('admin.kyc.rejectindex') ? 'active' : '' }}">Rejected KYC</a>
        </div>
    </div>

    <!-- Search & Filter Form Section -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light rounded">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Username or Name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Filter</button>
                </div>
                <div class="col-6 col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Data Table -->
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-dark text-nowrap">
                <tr>
                    <th>Username</th>
                    <th>Personal</th>
                    <th>Identity</th>
                    <th>PAN</th>
                    <th>Bank</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kycRequests as $k)
                <tr>
                    <td class="fw-bold text-secondary">{{ $k->user->username ?? ($k->user->name ?? 'N/A') }}</td>
                    
                    {{-- Personal Section Status --}}
                    <td>
                        <span class="badge {{ $k->kyc_status_personal == 'submitted' ? 'bg-primary' : ($k->kyc_status_personal == 'approved' ? 'bg-success' : ($k->kyc_status_personal == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                            {{ ucfirst($k->kyc_status_personal ?? 'Pending') }}
                        </span>
                    </td>

                    {{-- Identity Section Status --}}
                    <td>
                        <span class="badge {{ $k->kyc_status_identity == 'submitted' ? 'bg-primary' : ($k->kyc_status_identity == 'approved' ? 'bg-success' : ($k->kyc_status_identity == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                            {{ ucfirst($k->kyc_status_identity ?? 'Pending') }}
                        </span>
                    </td>

                    {{-- PAN Section Status --}}
                    <td>
                        <span class="badge {{ $k->kyc_status_pan == 'submitted' ? 'bg-primary' : ($k->kyc_status_pan == 'approved' ? 'bg-success' : ($k->kyc_status_pan == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                            {{ ucfirst($k->kyc_status_pan ?? 'Pending') }}
                        </span>
                    </td>

                    {{-- Bank Section Status --}}
                    <td>
                        <span class="badge {{ $k->kyc_status_bank == 'submitted' ? 'bg-primary' : ($k->kyc_status_bank == 'approved' ? 'bg-success' : ($k->kyc_status_bank == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                            {{ ucfirst($k->kyc_status_bank ?? 'Pending') }}
                        </span>
                    </td>
                    
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-dark px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#kycModal{{ $k->id }}">
                            <i class="fa fa-eye me-1"></i> Review & Action
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fa fa-folder-open d-block mb-2 fs-3"></i> No data found matching the current filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL FOR VIEWING DETAILS AND VERIFICATION --}}
@foreach ($kycRequests as $k)
<div class="modal fade" id="kycModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"> 
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fa fa-id-card me-2 text-warning"></i>KYC Review Panel — {{ $k->user->name ?? 'User Profile' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    
                    {{-- 1. PERSONAL DETAILS --}}
                    <div class="col-md-6 border-end">
                        <div class="p-2 rounded bg-light mb-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-primary mb-0">1. Personal Information 👤</h5>
                            <span class="badge {{ $k->kyc_status_personal == 'approved' ? 'bg-success' : ($k->kyc_status_personal == 'rejected' ? 'bg-danger' : 'bg-primary') }}">{{ ucfirst($k->kyc_status_personal ?? 'pending') }}</span>
                        </div>
                        <div class="ps-2">
                            <p class="mb-2"><strong>Full Name:</strong> {{ $k->first_name ?? 'N/A' }} {{ $k->last_name ?? '' }}</p>
                            <p class="mb-2"><strong>Date of Birth:</strong> {{ $k->dob ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Gender:</strong> {{ ucfirst($k->gender ?? 'N/A') }}</p>
                            @if (!empty($k->personal_remark))
                                <div class="alert alert-sm {{ $k->kyc_status_personal == 'rejected' ? 'alert-danger' : 'alert-warning' }} p-2 mt-2"><small><strong>Note:</strong> {{ $k->personal_remark }}</small></div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. IDENTITY (AADHAAR) DETAILS --}}
                    <div class="col-md-6">
                        <div class="p-2 rounded bg-light mb-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-info mb-0">2. Identity Verification 🆔</h5>
                            <span class="badge {{ $k->kyc_status_identity == 'approved' ? 'bg-success' : ($k->kyc_status_identity == 'rejected' ? 'bg-danger' : ($k->kyc_status_identity == 'pending' ? 'bg-secondary' : 'bg-primary')) }}">{{ ucfirst($k->kyc_status_identity ?? 'pending') }}</span>
                        </div>
                        <div class="ps-2">
                            <p class="mb-3"><strong>Aadhaar Number:</strong> <span class="bg-white px-2 py-1 border rounded text-dark fs-6 font-monospace">[Aadhaar Redacted]</span></p>
                            
                            <div class="row text-center g-2 mb-2">
                                <div class="col-6">
                                    <small class="d-block text-muted mb-1 fw-bold">Front Side</small>
                                    @if($k->front_image)
                                    <a href="{{ asset('storage/'.$k->front_image) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$k->front_image) }}" class="img-fluid rounded border shadow-sm zoom-image" style="max-height: 110px; object-fit: contain;">
                                    </a>
                                    @else
                                    <span class="text-danger border rounded d-block p-3 bg-white"><small>Not Uploaded / Deleted</small></span>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <small class="d-block text-muted mb-1 fw-bold">Back Side</small>
                                    @if($k->back_image)
                                    <a href="{{ asset('storage/'.$k->back_image) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$k->back_image) }}" class="img-fluid rounded border shadow-sm zoom-image" style="max-height: 110px; object-fit: contain;">
                                    </a>
                                    @else
                                    <span class="text-danger border rounded d-block p-3 bg-white"><small>Not Uploaded / Deleted</small></span>
                                    @endif
                                </div>
                            </div>
                            @if (!empty($k->identity_remark) || !empty($k->kyc_remark))
                                <div class="alert alert-sm {{ $k->kyc_status_identity == 'rejected' ? 'alert-danger' : 'alert-warning' }} p-2"><small><strong>Note:</strong> {{ $k->identity_remark ?? $k->kyc_remark }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-1 text-muted"></div>

                    {{-- 3. PAN DETAILS --}}
                    <div class="col-md-6 border-end">
                        <div class="p-2 rounded bg-light mb-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-warning mb-0">3. PAN Card Data 💳</h5>
                            <span class="badge {{ $k->kyc_status_pan == 'approved' ? 'bg-success' : ($k->kyc_status_pan == 'rejected' ? 'bg-danger' : ($k->kyc_status_pan == 'pending' ? 'bg-secondary' : 'bg-primary')) }}">{{ ucfirst($k->kyc_status_pan ?? 'pending') }}</span>
                        </div>
                        <div class="ps-2">
                            <p class="mb-3"><strong>PAN Number:</strong> <span class="bg-white px-2 py-1 border rounded font-monospace fw-bold">{{ $k->pan_no ?? 'N/A' }}</span></p>
                            
                            <div class="text-center mb-2">
                                @if($k->front_image_pan)
                                <a href="{{ asset('storage/'.$k->front_image_pan) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$k->front_image_pan) }}" class="img-fluid rounded border shadow-sm zoom-image" style="max-height: 110px; object-fit: contain;">
                                </a>
                                @else
                                <span class="text-danger border rounded d-inline-block p-3 bg-white w-50"><small>Not Uploaded / Deleted</small></span>
                                @endif
                            </div>
                            @if (!empty($k->pan_remark))
                                <div class="alert alert-sm {{ $k->kyc_status_pan == 'rejected' ? 'alert-danger' : 'alert-warning' }} p-2"><small><strong>Note:</strong> {{ $k->pan_remark }}</small></div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. BANK ACCOUNT DETAILS --}}
                    <div class="col-md-6">
                        <div class="p-2 rounded bg-light mb-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-success mb-0">4. Bank Account Info 🏦</h5>
                            <span class="badge {{ $k->kyc_status_bank == 'approved' ? 'bg-success' : ($k->kyc_status_bank == 'rejected' ? 'bg-danger' : ($k->kyc_status_bank == 'pending' ? 'bg-secondary' : 'bg-primary')) }}">{{ ucfirst($k->kyc_status_bank ?? 'pending') }}</span>
                        </div>
                        <div class="ps-2">
                            <div class="row g-2 mb-2">
                                <div class="col-sm-6">
                                    <p class="mb-1"><small class="text-muted d-block">Holder Name</small><strong>{{ $k->account_holder_name ?? 'N/A' }}</strong></p>
                                    <p class="mb-1"><small class="text-muted d-block">Account Number</small><strong>{{ $k->account_no ?? 'N/A' }}</strong></p>
                                    <p class="mb-1"><small class="text-muted d-block">Bank Name</small><strong>{{ $k->bank_name ?? 'N/A' }}</strong></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><small class="text-muted d-block">IFSC Code</small><strong>{{ $k->ifsc_code ?? 'N/A' }}</strong></p>
                                    <p class="mb-1"><small class="text-muted d-block">Account Type</small><strong>{{ $k->account_type ?? 'N/A' }}</strong></p>
                                    <p class="mb-1"><small class="text-muted d-block">Branch / City</small><strong>{{ $k->bank_branch ?? 'N/A' }}</strong></p>
                                </div>
                            </div>
                            @if(!$k->front_image_bank)
                                <span class="text-danger border rounded d-inline-block p-2 bg-white mb-2"><small>Bank document: Not Uploaded / Deleted</small></span>
                            @endif
                            @if (!empty($k->bank_remark))
                                <div class="alert alert-sm {{ $k->kyc_status_bank == 'rejected' ? 'alert-danger' : 'alert-warning' }} p-2"><small><strong>Note:</strong> {{ $k->bank_remark }}</small></div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            
            {{-- INDIVIDUAL ACTION SECTION (SECTION WISE SUBMIT CODES) --}}
            <div class="modal-footer bg-light p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                
                {{-- Left Actions Group (Personal & Identity) --}}
                <div class="d-flex flex-column gap-3 w-100 max-width-lg" style="flex: 1; min-width: 300px;">
                    
                    {{-- Personal Action Block --}}
                    <div class="border rounded p-2 bg-white shadow-sm">
                        <small class="fw-bold text-primary mb-1 d-block">Personal Section Action:</small>
                        @if($k->kyc_status_personal == 'submitted')
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                    @csrf <input type="hidden" name="section" value="personal">
                                    <button type="submit" class="btn btn-success btn-sm text-nowrap">Approve</button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex gap-1 m-0 w-100">
                                    @csrf <input type="hidden" name="section" value="personal">
                                    <input type="text" name="remark" class="form-control form-control-sm" placeholder="Reason..." required>
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @else
                            <span class="badge {{ $k->kyc_status_personal == 'approved' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($k->kyc_status_personal ?? 'No Request') }}</span>
                        @endif
                        <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="mt-2 m-0"
                              onsubmit="return confirm('Delete personal documents? The member can upload again.');">
                            @csrf <input type="hidden" name="section" value="personal">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete Docs</button>
                        </form>
                    </div>

                    {{-- Identity Action Block --}}
                    <div class="border rounded p-2 bg-white shadow-sm">
                        <small class="fw-bold text-info mb-1 d-block">Identity Section Action:</small>
                        @if($k->kyc_status_identity == 'submitted')
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                    @csrf <input type="hidden" name="section" value="identity">
                                    <button type="submit" class="btn btn-success btn-sm text-nowrap">Approve</button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex gap-1 m-0 w-100">
                                    @csrf <input type="hidden" name="section" value="identity">
                                    <input type="text" name="remark" class="form-control form-control-sm" placeholder="Reason..." required>
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @else
                            <span class="badge {{ $k->kyc_status_identity == 'approved' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($k->kyc_status_identity ?? 'No Request') }}</span>
                        @endif
                        <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="mt-2 m-0"
                              onsubmit="return confirm('Delete identity documents? The member can upload again.');">
                            @csrf <input type="hidden" name="section" value="identity">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete Docs</button>
                        </form>
                    </div>
                </div>

                {{-- Right Actions Group (PAN & Bank) --}}
                <div class="d-flex flex-column gap-3 w-100" style="flex: 1; min-width: 300px;">
                    
                    {{-- PAN Action Block --}}
                    <div class="border rounded p-2 bg-white shadow-sm">
                        <small class="fw-bold text-warning mb-1 d-block">PAN Section Action:</small>
                        @if($k->kyc_status_pan == 'submitted')
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                    @csrf <input type="hidden" name="section" value="pan">
                                    <button type="submit" class="btn btn-success btn-sm text-nowrap">Approve</button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex gap-1 m-0 w-100">
                                    @csrf <input type="hidden" name="section" value="pan">
                                    <input type="text" name="remark" class="form-control form-control-sm" placeholder="Reason..." required>
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @else
                            <span class="badge {{ $k->kyc_status_pan == 'approved' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($k->kyc_status_pan ?? 'No Request') }}</span>
                        @endif
                        <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="mt-2 m-0"
                              onsubmit="return confirm('Delete PAN documents? The member can upload again.');">
                            @csrf <input type="hidden" name="section" value="pan">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete Docs</button>
                        </form>
                    </div>

                    {{-- Bank Action Block --}}
                    <div class="border rounded p-2 bg-white shadow-sm">
                        <small class="fw-bold text-success mb-1 d-block">Bank Section Action:</small>
                        @if($k->kyc_status_bank == 'submitted')
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                    @csrf <input type="hidden" name="section" value="bank">
                                    <button type="submit" class="btn btn-success btn-sm text-nowrap">Approve</button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex gap-1 m-0 w-100">
                                    @csrf <input type="hidden" name="section" value="bank">
                                    <input type="text" name="remark" class="form-control form-control-sm" placeholder="Reason..." required>
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @else
                            <span class="badge {{ $k->kyc_status_bank == 'approved' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($k->kyc_status_bank ?? 'No Request') }}</span>
                        @endif
                        <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="mt-2 m-0"
                              onsubmit="return confirm('Delete bank documents? The member can upload again.');">
                            @csrf <input type="hidden" name="section" value="bank">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete Docs</button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- FULLY VERIFIED CHECK OUT FOOTER --}}
            @if ($k->kyc_status_personal == 'approved' && $k->kyc_status_identity == 'approved' && $k->kyc_status_pan == 'approved' && $k->kyc_status_bank == 'approved')
                <div class="modal-footer bg-success-subtle border-0 text-center py-2">
                    <span class="text-success fw-bold w-100"><i class="fa fa-check-circle me-1"></i> PROFILE ALL COMPONENT VERIFIED</span>
                </div>
            @endif

        </div>
    </div>
</div>
@endforeach

@endsection