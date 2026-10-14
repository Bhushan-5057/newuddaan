@extends('user.layouts.app')
@section('content')
<style>
    

.email-wrap {
    word-break: break-all;      /* email ko tod ke next line me layega */
    overflow-wrap: anywhere;    /* modern browsers ke liye */
    white-space: normal;
}

</style>


    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">User Profile /</span> Profile
        </h4>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="../../assets/img/pages/profile-banner.png" alt="Banner image" class="img-fluid rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                                alt="user image"    style="width: 100px;"  class="d-block h-auto rounded-3  user-profile-img" />
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
                               @if (Advance_info('Registration_Type', 'normal') === 'normal')
                                    </div>
                                    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary text-nowrap">
                                        <i class="bx bx-user-check me-1"></i>Edit Profile
                                    </a>
                                </div>
                                @endif
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
                                <span>{{ Advance_info('register_name', 'yes') === 'yes' ? Auth::user()->name : Auth::user()->wallet_address }}</span>
                                
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user bx-xs"></i><span class="fw-medium mx-2">User Name:</span>
                                <span>{{ Auth::user()->username }}</span>
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
                                <i class="bx bx-flag bx-xs"></i>
                                <span class="fw-medium mx-2">Country:</span>

                                @php
                                    $country = 'N/A';

                                    if (!empty(Auth::user()->custom_fields[30]) && is_array(Auth::user()->custom_fields[30])) {
                                        $country = Auth::user()->custom_fields[30]['country_name'] ?? 'N/A';
                                    }
                                @endphp

                                <span>{{ $country }}</span>
                            </li>

                              @if (Advance_info('register_email', 'yes') === 'yes')
                  
                          <li class="d-flex align-items-start mb-3">
                            <i class="bx bx-envelope bx-xs mt-1"></i>
                            <span class="fw-medium mx-2">Email:</span>
                            <span class="email-wrap">{{ Auth::user()->email }}</span>
                        </li>
                        @endif
                      

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
                                    $customFields = \App\Models\CustomField::all()->keyBy('id');
                                    $userCustomFields = Auth::user()->custom_fields ?? [];
                                @endphp

                                @if (!empty($userCustomFields))
                                <ul class="list-unstyled mb-4">
                                @foreach ($userCustomFields as $fieldId => $value)

                                    @php
                                        $field = $customFields[$fieldId] ?? null;

                                        // Skip privacy / terms
                                        if ($field && $field->id == 6) {
                                            continue;
                                        }

                                        $displayValue = 'N/A';
                                        $countryIso   = null;

                                        if (is_array($value)) {

                                            if (!empty($value['full_number'])) {
                                                $displayValue = $value['full_number'];
                                            } elseif (!empty($value['country_code']) && !empty($value['number'])) {
                                                $displayValue = $value['country_code'].' '.$value['number'];
                                            }

                                            $countryIso = $value['country_iso'] ?? null;

                                        } else {
                                            $displayValue = $value;
                                        }
                                        
                                        
                                                if (!empty($value['full_number'])) {
                                    // +8527895263145 → +85 27895263145
                                    $displayValue = preg_replace('/^(\+\d{1,4})(\d+)/', '$1 $2', $value['full_number']);
                                }

        

                                        @endphp

                                        @if ($field)
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="bx bx-info-circle bx-xs"></i>
                                        <span class="fw-medium mx-2">{{ $field->name }}:</span>

                                        @if($countryIso)
                                        
                                            <span class="iti__flag iti__{{ $countryIso }}"
                                                title="{{ $value['country_name'] ?? '' }}"
                                                style="margin-right:6px; display:inline-block;"></span>
                                        @endif
                                        
                                        

                                        <span>{{ $displayValue }}</span>
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

        {{-- Full read-only profile summary --}}
        @include('user.user_profile._details')

    @endsection
    @section('scripts')
    <!-- jQuery -->
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <!-- DataTables JS -->
    
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @endsection
