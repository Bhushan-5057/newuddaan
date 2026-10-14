@extends('admin.layouts.app') 

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Add Admin Power Business (BV)</h4>
                    </div>
                </div>
                <div class="card-body">
                    
                    {{-- Success & Error Alert Flashes --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Power Business Submission Form --}}
                    <form action="{{ route('admin.power.submit') }}" method="POST">
                        @csrf
                        
                        {{-- Username Input --}}
                        <div class="form-group mb-3">
                            <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Enter username" required>
                        </div>

                        {{-- Matching/Business Type Dropdown --}}
                        <div class="form-group mb-3">
                            <label class="form-label" for="matching_type">Business Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="matching_type" name="matching_type" required>
                                <option value="" selected disabled>Select Business Type</option>
                                <option value="topup" {{ old('matching_type') == 'topup' ? 'selected' : '' }}>Purchase (Topup)</option>
                                <option value="retopup" {{ old('matching_type') == 'retopup' ? 'selected' : '' }}>RePurchase (Retopup)</option>
                            </select>
                        </div>

                        {{-- Binary Side / Position Dropdown --}}
                        <div class="form-group mb-3">
                            <label class="form-label" for="position">Binary Side Position <span class="text-danger">*</span></label>
                            <select class="form-select" id="position" name="position" required>
                                <option value="" selected disabled>Select Position Side</option>
                                <option value="1" {{ old('position') == '1' ? 'selected' : '' }}>Left Side</option>
                                <option value="2" {{ old('position') == '2' ? 'selected' : '' }}>Right Side</option>
                            </select>
                        </div>

                        {{-- BV Amount Input --}}
                        <div class="form-group mb-3">
                            <label class="form-label" for="amount">Power BV Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter Business Volume (BV) amount" required>
                        </div>

                        {{-- Remarks Input --}}
                        <div class="form-group mb-4">
                            <label class="form-label" for="remark">Remark (Optional)</label>
                            <input type="text" class="form-control" id="remark" name="remark" value="{{ old('remark') }}" placeholder="Enter any specific note">
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Grant Power Business</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection