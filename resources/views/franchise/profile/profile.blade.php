@extends('franchise.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="h4 fw-bold mb-1">
            {{ $franchise->name }} 
            <span class="text-muted fs-6 fw-normal">({{ $franchise->username }})</span>
        </h2>
        <p class="text-muted small">My Profile Information</p>
    </div>

    {{-- ================= VIEW PROFILE (INFO CARDS) ================= --}}
    <div class="row g-4 mb-4">
        
        {{-- BASIC INFO --}}
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-4 border-bottom pb-2">Basic Information</h6>
                    <div class="small">
                        <p class="mb-2"><strong>Name:</strong> {{ $franchise->name }}</p>
                        <p class="mb-2"><strong>Username:</strong> {{ $franchise->username }}</p>
                        <p class="mb-2"><strong>Email:</strong> {{ $franchise->email }}</p>
                        <p class="mb-2"><strong>Mobile:</strong> {{ $franchise->mobile }}</p>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            <span class="badge {{ $franchise->active_status ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }}">
                                {{ $franchise->active_status ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FRANCHISE DETAILS --}}
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-4 border-bottom pb-2">Franchise Details</h6>
                    <div class="small">
                        <p class="mb-2"><strong>Franchise Name:</strong> {{ $franchise->franchise_name }}</p>
                        <p class="mb-2"><strong>PAN:</strong> {{ $franchise->pan_no ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>GST:</strong> {{ $franchise->franchise_gst ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Registered:</strong> {{ $franchise->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-4 border-bottom pb-2">Address</h6>
                    <div class="small text-capitalize">
                        <p class="mb-2"><strong>Country:</strong> {{ $franchise->country }}</p>
                        <p class="mb-2"><strong>State:</strong> {{ $franchise->state }}</p>
                        <p class="mb-2"><strong>City:</strong> {{ $franchise->city }}</p>
                        <p class="mb-2"><strong>Pin Code:</strong> {{ $franchise->pin_code }}</p>
                        <p class="mb-0 text-muted"><strong>Full Address:</strong> {{ $franchise->address }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= UPDATE + PASSWORD (FORMS) ================= --}}
    <div class="row g-4">

        {{-- UPDATE PROFILE FORM --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Update Profile</h6>
                    
                    <form method="POST" action="{{ route('franchise.profile.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Name</label>
                                <input type="text" name="name" value="{{ old('name', $franchise->name) }}" class="form-control bg-light border-0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" name="email" value="{{ old('email', $franchise->email) }}" class="form-control bg-light border-0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile', $franchise->mobile) }}" class="form-control bg-light border-0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">City</label>
                                <input type="text" name="city" value="{{ old('city', $franchise->city) }}" class="form-control bg-light border-0">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Address</label>
                                <textarea name="address" rows="3" class="form-control bg-light border-0">{{ old('address', $franchise->address) }}</textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- CHANGE PASSWORD FORM --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-bold mb-4">Change Password</h6>
                    
                    <form method="POST" action="{{ route('franchise.profile.update') }}" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="password_only" value="1">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Current Password</label>
                            <input type="password" name="current_password" class="form-control bg-light border-0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">New Password</label>
                            <input type="password" name="password" class="form-control bg-light border-0">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-light border-0">
                        </div>
                        
                        <div class="mt-auto">
                            <button type="submit" class="btn btn-dark w-100 shadow-sm">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Custom Style for Soft Badges --}}
<style>
    .bg-light-success { background-color: #e8fff3; color: #50cd89; }
    .bg-light-secondary { background-color: #f5f8fa; color: #7e8299; }
    .form-control:focus { background-color: #fff; box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1); border-color: #007bff; }
</style>
@endsection