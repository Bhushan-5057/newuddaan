@extends('admin.layouts.app')
@section('title','Franchise Dashboard')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            {{ $franchise->name }}
            <small class="text-muted">
                ({{ $franchise->username }})
            </small>
        </h4>
        <small class="text-muted">Franchise Details</small>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.franchise.index') }}"
           class="btn btn-outline-secondary">
            ← Back to List
        </a>

        <a href="{{ route('admin.franchise.create') }}"
           class="btn btn-primary">
            + Add Franchise
        </a>
    </div>
</div>


    {{-- BASIC INFO --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="text-muted mb-2">Basic Information</h6>

                <p class="mb-1"><b>Name:</b> {{ $franchise->name }}</p>
                <p class="mb-1"><b>Username:</b> {{ $franchise->username }}</p>
                <p class="mb-1"><b>Email:</b> {{ $franchise->email }}</p>
                <p class="mb-1"><b>Mobile:</b> {{ $franchise->mobile }}</p>

                <p class="mb-0">
                    <b>Status:</b>
                    @if($franchise->active_status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- FRANCHISE DETAILS --}}
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="text-muted mb-2">Franchise Details</h6>

                <p class="mb-1"><b>Franchise Name:</b> {{ $franchise->franchise_name }}</p>
                <p class="mb-1"><b>PAN:</b> {{ $franchise->pan_no ?? 'N/A' }}</p>
                <p class="mb-1"><b>GST:</b> {{ $franchise->franchise_gst ?? 'N/A' }}</p>

                <p class="mb-0">
                    <b>Registered On:</b>
                    {{ $franchise->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="text-muted mb-2">Address</h6>

                <p class="mb-1"><b>Country:</b> {{ $franchise->country }}</p>
                <p class="mb-1"><b>State:</b> {{ $franchise->state }}</p>
                <p class="mb-1"><b>City:</b> {{ $franchise->city }}</p>
                <p class="mb-1"><b>Pin Code:</b> {{ $franchise->pin_code }}</p>
                <p class="mb-0"><b>Address:</b> {{ $franchise->address }}</p>
            </div>
        </div>

    </div>

    {{-- NOMINEE INFO --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="text-muted mb-2">Nominee Information</h6>

                <p class="mb-1">
                    <b>Relation:</b>
                    {{ $franchise->nominee_relation ?? 'N/A' }}
                </p>

                <p class="mb-0">
                    <b>Name:</b>
                    {{ $franchise->nominee_name ?? 'N/A' }}
                </p>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="text-muted mb-2">Quick Actions</h6>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.franchise.index') }}"
                       class="btn btn-outline-secondary">
                        Franchise List
                    </a>

                    <a href="{{ route('admin.franchise.create') }}"
                       class="btn btn-primary">
                        Add New Franchise
                    </a>

                    {{-- Future buttons --}}
                    <button class="btn btn-outline-info" disabled>
                        Orders (Coming Soon)
                    </button>

                    <button class="btn btn-outline-warning" disabled>
                        Commission (Coming Soon)
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER NOTE --}}
    <div class="alert alert-info">
        <b>Note:</b>
        Orders, stock management, commission reports and login history
        will be available in future updates.
    </div>

</div>
@endsection
