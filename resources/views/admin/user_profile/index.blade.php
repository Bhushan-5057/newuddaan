@extends('admin.layouts.app')

@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">User Profile /</span> Profile
    </h4>

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="../../assets/img/pages/profile-banner.png" alt="Banner image" class="rounded-top" />
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                            alt="user image" class="d-block h-auto rounded-3 user-profile-img" />


                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ Auth::user()->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item fw-medium"><i
                                            class="bx bx-pen"></i>{{ ucfirst(Auth::user()->role) }}</li>
                                    <!-- <li class="list-inline-item fw-medium"><i class="bx bx-map"></i> Vatican City</li> -->
                                    <li class="list-inline-item fw-medium">
                                        <i class="bx bx-calendar-alt"></i>
                                        {{ Auth::user()->created_at->format('d M, Y') }}
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary text-nowrap">
                                <i class="bx bx-user-check me-1"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->


    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-text text-uppercase">About</p>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-user bx-xs"></i><span class="fw-medium mx-2">Full Name:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </li>
                        <!-- <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-check bx-xs"></i><span class="fw-medium mx-2">Status:</span>
                            <span>Active</span>
                        </li> -->
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-star bx-xs"></i><span class="fw-medium mx-2">Role:</span>
                            <span>{{ ucfirst(Auth::user()->role) }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-flag bx-xs"></i><span class="fw-medium mx-2">Country:</span>
                            <span>USA</span>
                        </li>
                        <!-- <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-detail bx-xs"></i><span class="fw-medium mx-2">Languages:</span>
                            <span>English</span>
                        </li> -->

                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-envelope bx-xs"></i><span class="fw-medium mx-2">Email:</span>
                            <span>{{ Auth::user()->email }}</span>
                        </li>
                    </ul>

                </div>
            </div>
        </div>


        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Activity Timeline -->
            <div class="card card-action mb-4">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0"><i class="bx bx-list-ul bx-sm me-2"></i>Additional Info
                    </h5>

                </div>
                <div class="card-body">
                    <span class="timeline-point timeline-point-warning"></span>
                    <div class="timeline-event">

                        <!--/ About User -->
                        @php
                        $customFields = \App\Models\CustomField::all()->keyBy('id'); // Cache by ID
                        $userCustomFields = Auth::user()->custom_fields ?? [];
                        @endphp

                        @if (!empty($userCustomFields))
                        <ul class="list-unstyled mb-4">
                            @foreach ($userCustomFields as $fieldId => $value)
                            @php
                            $field = $customFields[$fieldId] ?? null;

                            if ($field && $field->id == 6) {
                            continue;
                            }

                            @endphp
                            @if ($field)
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-info-circle bx-xs"></i>
                                <span class="fw-medium mx-2">{{ $field->name ?? 'Field' }}:</span>
                                <span>{{ $value }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        @endif


                        <!--/ Projects table -->
                    </div>
                </div>
                <!--/ User Profile Content -->
            </div>
            <!-- / Content -->
        </div>
    </div>
</div>


@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


@endsection