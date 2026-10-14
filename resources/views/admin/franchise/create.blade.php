@extends('admin.layouts.app')
@section('title', 'Register Franchise')

@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Register Franchise</h4>

            <a href="{{ route('admin.franchise.index') }}" class="btn btn-outline-primary">
                <i class="bx bx-list-ul me-1"></i> Franchise List
            </a>
        </div>


        <form method="POST" action="{{ route('admin.franchise.store') }}" class="card p-4">
            @csrf

            <div class="row g-3">

                {{-- LEFT COLUMN --}}
                <div class="col-md-6">

                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        class="form-control @error('username') is-invalid @enderror">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}"
                        class="form-control @error('mobile') is-invalid @enderror">
                    @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Country</label>
                    <input type="text" name="country" value="{{ old('country') }}"
                        class="form-control @error('country') is-invalid @enderror">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">State</label>
                    <input type="text" name="state" value="{{ old('state') }}"
                        class="form-control @error('state') is-invalid @enderror">
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Pin Code</label>
                    <input type="text" name="pin_code" value="{{ old('pin_code') }}"
                        class="form-control @error('pin_code') is-invalid @enderror">
                    @error('pin_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-md-6">

                    <label>Nominee Relation</label>
                    <input type="text" name="nominee_relation" value="{{ old('nominee_relation') }}"
                        class="form-control @error('nominee_relation') is-invalid @enderror">

                    <label class="mt-2">Nominee Name</label>
                    <input type="text" name="nominee_name" value="{{ old('nominee_name') }}"
                        class="form-control @error('nominee_name') is-invalid @enderror">

                    <label class="mt-2">Franchise Name</label>
                    <input type="text" name="franchise_name" value="{{ old('franchise_name') }}"
                        class="form-control @error('franchise_name') is-invalid @enderror">
                    @error('franchise_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Franchise PAN</label>
                    <input type="text" name="pan_no" value="{{ old('pan_no') }}"
                        class="form-control @error('pan_no') is-invalid @enderror">

                    <label class="mt-2">Franchise GST</label>
                    <input type="text" name="franchise_gst" value="{{ old('franchise_gst') }}"
                        class="form-control @error('franchise_gst') is-invalid @enderror">

                    <label class="mt-2">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="form-control @error('city') is-invalid @enderror">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label class="mt-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">

                </div>

            </div>

            <button class="btn btn-primary mt-4">SAVE</button>

        </form>
    </div>
@endsection
