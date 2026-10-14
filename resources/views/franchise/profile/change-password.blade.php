@extends('franchise.layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="container-fluid py-5 d-flex justify-content-center">
    <div class="w-100" style="max-width: 500px;">

        <div class="card border-0 shadow-sm p-4 p-md-5">
            <div class="card-body">
                <h2 class="h4 fw-bold mb-4">Change Password</h2>

                <form method="POST" action="{{ route('franchise.profile.update') }}">
                    @csrf
                    <input type="hidden" name="password_only" value="1">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Current Password</label>
                        <input type="password" name="current_password" 
                               class="form-control form-control-lg bg-light border-0 fs-6" 
                               placeholder="Enter current password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">New Password</label>
                        <input type="password" name="password" 
                               class="form-control form-control-lg bg-light border-0 fs-6" 
                               placeholder="Enter new password">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                               class="form-control form-control-lg bg-light border-0 fs-6" 
                               placeholder="Re-type new password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    /* ફોર્મ ઇનપુટ પર ફોકસ આવે ત્યારે સુંદર બોર્ડર માટે */
    .form-control:focus {
        background-color: #fff !important;
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
    }
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }
</style>
@endsection