@extends('admin.layouts.app')

@section('title', 'KYC Requests')

@section('content')
<style>
.zoom-image {
    transition: transform 0.3s ease-in-out;
    cursor: zoom-in;
}
.zoom-image:hover {
    transform: scale(1.8);
    z-index: 999;
    position: relative;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>KYC Management</h2>
        <div class="btn-group">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-primary {{ Route::is('admin.kyc.index') ? 'active' : '' }}">Pending Requests</a>
            <a href="{{ route('admin.kyc.approvedindex') }}" class="btn btn-outline-success {{ Route::is('admin.kyc.approvedindex') ? 'active' : '' }}">Approved KYC</a>
            <a href="{{ route('admin.kyc.rejectindex') }}" class="btn btn-outline-danger {{ Route::is('admin.kyc.rejectindex') ? 'active' : '' }}">Rejected KYC</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by User Name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Status (Personal)</th>
                    <th>Status (Identity)</th>
                    <th>Status (PAN)</th>
                    <th>Status (Bank)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kycRequests as $request)
                    <tr>
                        <td>{{ $request->user->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $request->kyc_status_personal == 'submitted' ? 'bg-primary' : ($request->kyc_status_personal == 'approved' ? 'bg-success' : ($request->kyc_status_personal == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                {{ ucfirst($request->kyc_status_personal ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $request->kyc_status_identity == 'submitted' ? 'bg-primary' : ($request->kyc_status_identity == 'approved' ? 'bg-success' : ($request->kyc_status_identity == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                {{ ucfirst($request->kyc_status_identity ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $request->kyc_status_pan == 'submitted' ? 'bg-primary' : ($request->kyc_status_pan == 'approved' ? 'bg-success' : ($request->kyc_status_pan == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                {{ ucfirst($request->kyc_status_pan ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $request->kyc_status_bank == 'submitted' ? 'bg-primary' : ($request->kyc_status_bank == 'approved' ? 'bg-success' : ($request->kyc_status_bank == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                {{ ucfirst($request->kyc_status_bank ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            @if ($request->kyc_status_personal == 'submitted')
                                <button type="button" class="btn btn-primary btn-sm my-1" data-bs-toggle="modal" data-bs-target="#personalModal{{ $request->id }}">Personal</button>
                            @endif
                            @if ($request->kyc_status_identity == 'submitted')
                                <button type="button" class="btn btn-info btn-sm my-1" data-bs-toggle="modal" data-bs-target="#identityModal{{ $request->id }}">Identity</button>
                            @endif
                            @if ($request->kyc_status_pan == 'submitted')
                                <button type="button" class="btn btn-warning btn-sm my-1" data-bs-toggle="modal" data-bs-target="#panModal{{ $request->id }}">PAN</button>
                            @endif
                            @if ($request->kyc_status_bank == 'submitted')
                                <button type="button" class="btn btn-success btn-sm my-1" data-bs-toggle="modal" data-bs-target="#bankModal{{ $request->id }}">Bank</button>
                            @endif
                            
                            @if(!in_array('submitted', [$request->kyc_status_personal, $request->kyc_status_identity, $request->kyc_status_pan, $request->kyc_status_bank]))
                                <span class="text-muted text-sm">No Action Pending</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No KYC requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


    <!-- Personal Details Modal -->
    @foreach ($kycRequests as $kycDetail)
        <div class="modal fade" id="personalModal{{ $kycDetail->id }}" tabindex="-1"
            aria-labelledby="personalModalLabel{{ $kycDetail->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="personalModalLabel{{ $kycDetail->id }}">KYC Personal Details for
                            {{ $kycDetail->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header">Personal Details</div>
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $kycDetail->name }}</p>
                                <p><strong>Email:</strong> {{ $kycDetail->email }}</p>
                                <p><strong>Mobile:</strong> {{ $kycDetail->mobile }}</p>
                                <p><strong>Status:</strong> <span
                                        class="badge {{ $kycDetail->kyc_status_personal == 'submitted' ? 'bg-primary' : ($kycDetail->kyc_status_personal == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($kycDetail->kyc_status_personal) }}</span>
                                </p>
                                 @if ($kycDetail->profile_pic)
                                    <a href="{{ asset('storage/' . $kycDetail->profile_pic) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $kycDetail->profile_pic) }}" class="img-fluid zoom-image rounded" style="max-width:150px;">
                                    </a>
                                @endif

                                @if ($kycDetail->kyc_status_personal == 'rejected')
                                    <p class="mt-2 text-danger"><strong>Reason:</strong> {{ $kycDetail->personal_remark }}
                                    </p>
                                @endif
                            </div>
                            @if ($kycDetail->kyc_status_personal == 'submitted')
                                <div class="card-footer d-flex justify-content-end">
                                    <form action="{{ route('admin.kyc.approve', $kycDetail) }}" method="POST"
                                        class="me-2">
                                        @csrf
                                        <input type="hidden" name="section" value="personal">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.kyc.reject', $kycDetail) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="section" value="personal">
                                        <textarea name="remark" class="form-control form-control-sm me-2" placeholder="Rejection reason..." required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm mt-2">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Identity Verification Modal -->
    @foreach ($kycRequests as $kycDetail)
        <div class="modal fade" id="identityModal{{ $kycDetail->id }}" tabindex="-1"
            aria-labelledby="identityModalLabel{{ $kycDetail->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="identityModalLabel{{ $kycDetail->id }}">KYC Identity Details for
                            {{ $kycDetail->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header">Identity Verification</div>
                            <div class="card-body">
                                <p><strong>Document Type:</strong> {{ $kycDetail->attached_doc }}</p>
                                <p><strong>Document No:</strong> {{ $kycDetail->tax_id }}</p>
                                <p><strong>Status:</strong> <span
                                        class="badge {{ $kycDetail->kyc_status_identity == 'submitted' ? 'bg-primary' : ($kycDetail->kyc_status_identity == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($kycDetail->kyc_status_identity) }}</span>
                                </p>
                               @if ($kycDetail->front_image)
                                    <a href="{{ asset('storage/'.$kycDetail->front_image) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$kycDetail->front_image) }}" class="img-fluid zoom-image rounded shadow" style="max-width:200px;">
                                    </a>
                                @endif
                                
                                @if ($kycDetail->back_image)
                                    <a href="{{ asset('storage/'.$kycDetail->back_image) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$kycDetail->back_image) }}" class="img-fluid zoom-image rounded shadow" style="max-width:200px;">
                                    </a>
                                @endif

                                @if ($kycDetail->kyc_status_identity == 'rejected')
                                    <p class="mt-2 text-danger"><strong>Reason:</strong> {{ $kycDetail->kyc_remark }}</p>
                                @endif
                            </div>
                            @if ($kycDetail->kyc_status_identity == 'submitted')
                                <div class="card-footer d-flex justify-content-end">
                                    <form action="{{ route('admin.kyc.approve', $kycDetail) }}" method="POST"
                                        class="me-2">
                                        @csrf
                                        <input type="hidden" name="section" value="identity">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.kyc.reject', $kycDetail) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="section" value="identity">
                                        <textarea name="remark" class="form-control form-control-sm me-2" placeholder="Rejection reason..." required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm mt-2">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pan Card Details Modal -->
    @foreach ($kycRequests as $kycDetail)
        <div class="modal fade" id="panModal{{ $kycDetail->id }}" tabindex="-1"
            aria-labelledby="panModalLabel{{ $kycDetail->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="panModalLabel{{ $kycDetail->id }}">KYC PAN Details for
                            {{ $kycDetail->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header">PAN Card Details</div>
                            <div class="card-body">
                                <p><strong>PAN No:</strong> {{ $kycDetail->pan_no }}</p>
                                <p><strong>Status:</strong> <span
                                        class="badge {{ $kycDetail->kyc_status_pan == 'submitted' ? 'bg-primary' : ($kycDetail->kyc_status_pan == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($kycDetail->kyc_status_pan) }}</span>
                                </p>
                                @if ($kycDetail->front_image_pan)
                                    <a href="{{ asset('storage/' . $kycDetail->front_image_pan) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $kycDetail->front_image_pan) }}" class="img-fluid zoom-image rounded" style="max-width: 200px;">
                                    </a>
                                @endif

                                @if ($kycDetail->kyc_status_pan == 'rejected')
                                    <p class="mt-2 text-danger"><strong>Reason:</strong> {{ $kycDetail->pan_remark }}</p>
                                @endif
                            </div>
                            @if ($kycDetail->kyc_status_pan == 'submitted')
                                <div class="card-footer d-flex justify-content-end">
                                    <form action="{{ route('admin.kyc.approve', $kycDetail) }}" method="POST"
                                        class="me-2">
                                        @csrf
                                        <input type="hidden" name="section" value="pan">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.kyc.reject', $kycDetail) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="section" value="pan">
                                        <textarea name="remark" class="form-control form-control-sm me-2" placeholder="Rejection reason..." required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm mt-2">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Bank Details Modal -->
    @foreach ($kycRequests as $kycDetail)
        <div class="modal fade" id="bankModal{{ $kycDetail->id }}" tabindex="-1"
            aria-labelledby="bankModalLabel{{ $kycDetail->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bankModalLabel{{ $kycDetail->id }}">KYC Bank Details for
                            {{ $kycDetail->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header">Bank Details</div>
                            <div class="card-body">
                                <p><strong>Account Holder Name:</strong> {{ $kycDetail->account_holder_name }}</p>
                                <p><strong>Bank Name:</strong> {{ $kycDetail->bank_name }}</p>
                                <p><strong>Account No:</strong> {{ $kycDetail->account_no }}</p>
                                <p><strong>IFSC Code:</strong> {{ $kycDetail->ifsc_code }}</p>
                                <p><strong>Bank Branch:</strong> {{ $kycDetail->bank_branch }}</p>
                                <p><strong>Status:</strong> <span
                                        class="badge {{ $kycDetail->kyc_status_bank == 'submitted' ? 'bg-primary' : ($kycDetail->kyc_status_bank == 'approved' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($kycDetail->kyc_status_bank) }}</span>
                                </p>
                               @if ($kycDetail->front_image_bank)
                                    <a href="{{ asset('storage/' . $kycDetail->front_image_bank) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $kycDetail->front_image_bank) }}" class="img-fluid zoom-image rounded" style="max-width: 200px;">
                                    </a>
                                @endif

                                @if ($kycDetail->kyc_status_bank == 'rejected')
                                    <p class="mt-2 text-danger"><strong>Reason:</strong> {{ $kycDetail->bank_remark }}</p>
                                @endif
                            </div>
                            @if ($kycDetail->kyc_status_bank == 'submitted')
                                <div class="card-footer d-flex justify-content-end">
                                    <form action="{{ route('admin.kyc.approve', $kycDetail) }}" method="POST"
                                        class="me-2">
                                        @csrf
                                        <input type="hidden" name="section" value="bank">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.kyc.reject', $kycDetail) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="section" value="bank">
                                        <textarea name="remark" class="form-control form-control-sm me-2" placeholder="Rejection reason..." required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm mt-2">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
