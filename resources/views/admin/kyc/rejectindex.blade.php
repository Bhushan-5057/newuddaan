@extends('admin.layouts.app')

@section('title', 'KYC Verification Requests')

@section('content')

<div class="container py-4">
    <h2>Rejected KYC Verification Requests</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Form Section (Username, Name, or Mobile No) -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light rounded">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Username, Name " value="{{ request('search') }}">
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

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    {{-- CONSOLIDATED STATUS COLUMN --}}
                    <th>Status (Identity, PAN & Bank)</th> 
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kycRequests as $k)
                <tr>
                    <td>{{ $k->user->username }}</td>
                    
                    {{-- Consolidated Status Logic --}}
                    <td>
                        @php
                            $identityStatus = $k->kyc_status_identity ?? 'pending';
                            $panStatus = $k->kyc_status_pan ?? 'pending';
                            $bankStatus = $k->kyc_status_bank ?? 'pending';
                            
                            if ($identityStatus === 'rejected' || $panStatus === 'rejected' || $bankStatus === 'rejected') {
                                $displayStatus = 'Rejected';
                                $badgeClass = 'bg-danger';
                            } elseif ($identityStatus === 'approved' && $panStatus === 'approved' && $bankStatus === 'approved') {
                                $displayStatus = 'Approved';
                                $badgeClass = 'bg-success';
                            } elseif ($identityStatus === 'submitted' || $panStatus === 'submitted' || $bankStatus === 'submitted') {
                                $displayStatus = 'Submitted';
                                $badgeClass = 'bg-primary';
                            } else {
                                $displayStatus = 'Pending';
                                $badgeClass = 'bg-secondary';
                            }
                        @endphp

                        <span class="badge {{ $badgeClass }}">
                            {{ $displayStatus }}
                        </span>
                    </td>
                    
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#kycModal{{ $k->id }}">
                            View & Verify
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

---

{{-- MODAL FOR VIEWING DETAILS AND VERIFICATION --}}
@foreach ($kycRequests as $k)
<div class="modal fade" id="kycModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"> 
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">KYC Verification - {{ $k->user->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    
                    {{-- LEFT COLUMN: IDENTITY & PAN DETAILS --}}
                    <div class="col-lg-6 border-end">
                        <h4 class="fw-bold text-primary mb-3">Identity Documents ðŸ†”</h4>
                        
                        {{-- Aadhaar --}}
                        <h6 class="fw-bold text-info">Aadhaar Details</h6>
                        <p><strong>Aadhaar Number:</strong> <span class="text-muted">[Aadhaar Redacted]</span></p>

                        <div class="row mb-3">
                            <div class="col-6 text-center">
                                <p class="mb-1">Front</p>
                                @if($k->front_image)
                                <a href="{{ public_media($k->front_image) }}" target="_blank">
                                    <img src="{{ public_media($k->front_image) }}" class="img-fluid rounded shadow" style="max-width:200px;">
                                </a>
                                @else
                                <span class="text-danger">Not Uploaded</span>
                                @endif
                            </div>

                            <div class="col-6 text-center">
                                <p class="mb-1">Back</p>
                                @if($k->back_image)
                                <a href="{{ public_media($k->back_image) }}" target="_blank">
                                    <img src="{{ public_media($k->back_image) }}" class="img-fluid rounded shadow" style="max-width:200px;">
                                </a>
                                @else
                                <span class="text-danger">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Identity Status/Remark Display --}}
                        <h6 class="mt-2">Identity Status: <span class="badge {{ $k->kyc_status_identity == 'submitted' ? 'bg-primary' : ($k->kyc_status_identity == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($k->kyc_status_identity ?? 'pending') }}</span></h6>
                        @if ($k->kyc_status_identity == 'rejected')
                            <p class="text-danger"><strong>Rejection Reason:</strong> {{ $k->identity_remark }}</p>
                        @endif

                        <hr>

                        {{-- PAN --}}
                        <h6 class="fw-bold text-warning">PAN Details</h6>
                        <p><strong>PAN Number:</strong> {{ $k->pan_no }}</p>

                        <div class="text-center mb-3">
                            @if($k->front_image_pan)
                            <a href="{{ public_media($k->front_image_pan) }}" target="_blank">
                                <img src="{{ public_media($k->front_image_pan) }}" class="img-fluid rounded shadow" style="max-width:220px;">
                            </a>
                            @else
                            <span class="text-danger">Not Uploaded</span>
                            @endif
                        </div>
                        
                        {{-- PAN Status/Remark Display --}}
                        <h6>PAN Status: <span class="badge {{ $k->kyc_status_pan == 'submitted' ? 'bg-primary' : ($k->kyc_status_pan == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($k->kyc_status_pan ?? 'pending') }}</span></h6>
                        @if ($k->kyc_status_pan == 'rejected')
                            <p class="text-danger"><strong>Rejection Reason:</strong> {{ $k->pan_remark }}</p>
                        @endif
                        
                    </div>
                    
                    {{-- RIGHT COLUMN: BANK DETAILS --}}
                    <div class="col-lg-6">
                        <h4 class="fw-bold text-success mb-3">Bank Account Details ðŸ¦</h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Holder Name:</strong> {{ $k->account_holder_name ?? 'N/A' }}</p>
                                <p><strong>Account Number:</strong> {{ $k->account_no ?? 'N/A' }}</p>
                                <p><strong>Bank Name:</strong> {{ $k->bank_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>IFSC Code:</strong> {{ $k->ifsc_code ?? 'N/A' }}</p>
                                <p><strong>Account Type:</strong> {{ $k->account_type ?? 'N/A' }}</p>
                                <p><strong>Branch:</strong> {{ $k->bank_branch ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- BANK IMAGE / PASSBOOK SHOW --}}
                        <div class="text-center mb-3 border rounded p-2 bg-white shadow-sm">
                            <p class="mb-1 fw-bold text-muted text-start ps-2"><i class="fa fa-image me-1"></i> Passbook / Cheque Image:</p>
                            @php
                                $bankImg = $k->bank_image ?? ($k->passbook_image ?? null);
                            @endphp
                            @if($bankImg)
                                <a href="{{ public_media($bankImg) }}" target="_blank">
                                    <img src="{{ public_media($bankImg) }}" class="img-fluid rounded border shadow" style="max-height: 150px; object-fit: contain;">
                                </a>
                            @else
                                <span class="text-danger d-block p-3">Bank Document Image Not Uploaded</span>
                            @endif
                        </div>
                        
                        {{-- Bank Status/Remark Display --}}
                        <h6 class="mt-4">Bank Status: <span class="badge {{ ($k->kyc_status_bank ?? 'pending') == 'submitted' ? 'bg-info' : (($k->kyc_status_bank ?? 'pending') == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($k->kyc_status_bank ?? 'pending') }}</span></h6>
                        @if (($k->kyc_status_bank ?? 'pending') == 'rejected')
                            <p class="text-danger"><strong>Rejection Reason:</strong> {{ $k->bank_remark }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- MODAL FOOTER FOR ACTIONS --}}
            <div class="modal-footer d-flex flex-column align-items-stretch gap-3 bg-light">
                
                {{-- 1. IDENTITY ACTIONS --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap pb-2 border-bottom">
                    <h6 class="text-primary mb-0"><strong>Identity Action:</strong></h6>
                    @if (($k->kyc_status_identity ?? 'pending') == 'submitted' || ($k->kyc_status_identity ?? 'pending') == 'rejected')
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="section" value="identity">
                                <button type="submit" class="btn btn-success btn-sm">Approve Identity</button>
                            </form>

                            <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex align-items-center m-0">
                                @csrf
                                <input type="hidden" name="section" value="identity">
                                <input type="text" name="remark" class="form-control form-control-sm" placeholder="ID Rejection reason..." required style="width: 200px;">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Reject Identity</button>
                            </form>
                        </div>
                    @else
                        <span class="badge bg-success p-2">Identity Approved</span>
                    @endif
                    <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="m-0 ms-2"
                          onsubmit="return confirm('Delete identity documents? The member can upload again.');">
                        @csrf <input type="hidden" name="section" value="identity">
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete</button>
                    </form>
                </div>

                {{-- 2. PAN ACTIONS --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap pb-2 border-bottom">
                    <h6 class="text-warning mb-0"><strong>PAN Action:</strong></h6>
                    @if (($k->kyc_status_pan ?? 'pending') == 'submitted' || ($k->kyc_status_pan ?? 'pending') == 'rejected')
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="section" value="pan">
                                <button type="submit" class="btn btn-success btn-sm">Approve PAN</button>
                            </form>

                            <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex align-items-center m-0">
                                @csrf
                                <input type="hidden" name="section" value="pan">
                                <input type="text" name="remark" class="form-control form-control-sm" placeholder="PAN Rejection reason..." required style="width: 200px;">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Reject PAN</button>
                            </form>
                        </div>
                    @else
                        <span class="badge bg-success p-2">PAN Approved</span>
                    @endif
                    <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="m-0 ms-2"
                          onsubmit="return confirm('Delete PAN documents? The member can upload again.');">
                        @csrf <input type="hidden" name="section" value="pan">
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete</button>
                    </form>
                </div>

                {{-- 3. BANK ACTIONS --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="text-success mb-0"><strong>Bank Action:</strong></h6>
                    @if (($k->kyc_status_bank ?? 'pending') == 'submitted' || ($k->kyc_status_bank ?? 'pending') == 'rejected')
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <form action="{{ route('admin.kyc.approve', $k) }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="section" value="bank">
                                <button type="submit" class="btn btn-success btn-sm">Approve Bank</button>
                            </form>

                            <form action="{{ route('admin.kyc.reject', $k) }}" method="POST" class="d-flex align-items-center m-0">
                                @csrf
                                <input type="hidden" name="section" value="bank">
                                <input type="text" name="remark" class="form-control form-control-sm" placeholder="Bank Rejection reason..." required style="width: 200px;">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Reject Bank</button>
                            </form>
                        </div>
                    @else
                        <span class="badge bg-success p-2">Bank Approved</span>
                    @endif
                    <form action="{{ route('admin.kyc.deleteDocument', $k) }}" method="POST" class="m-0 ms-2"
                          onsubmit="return confirm('Delete bank documents? The member can upload again.');">
                        @csrf <input type="hidden" name="section" value="bank">
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i> Delete</button>
                    </form>
                </div>
                
            </div>
            
            {{-- ALL APPROVED FOOTER --}}
            @if ($k->kyc_status_identity == 'approved' && $k->kyc_status_pan == 'approved' && ($k->kyc_status_bank ?? 'pending') == 'approved')
                <div class="modal-footer">
                    <span class="badge bg-success p-2 w-100">âœ… ALL KYC SECTIONS APPROVED</span>
                </div>
            @endif

        </div>
    </div>
</div>
@endforeach

@endsection