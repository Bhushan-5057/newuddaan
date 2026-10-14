@extends('admin.layouts.app')

@section('content')
    <div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <div class="text-center border border-danger rounded p-5 bg-white shadow" style="max-width: 500px;">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
            </div>
            <h3 class="text-danger mb-3">Access Denied</h3>
            <p class="text-muted">You do not have permission to access this page.</p>
            <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-danger mt-3">
                <i class="fas fa-arrow-left me-2"></i> Go Back
            </a>
        </div>
    </div>
@endsection
