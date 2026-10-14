@extends('admin.layouts.app')


@section('content')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        .dataTables_scrollHead {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
        }
    </style>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Stats Cards with Improved Design -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-primary bg-opacity-10">
                                    <i class="bx bx-user fs-4 text-primary"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">{{ number_format($totalUsers) }}</h3>
                                <small class="text-muted">Total Users</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-primary rounded-pill me-2">All Time</span>
                                <small class="text-muted">Since launch</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-danger bg-opacity-10">
                                    <i class="bx bx-user-plus fs-4 text-danger"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">{{ number_format($lastWeekUsers) }}</h3>
                                <small class="text-muted">New This Week</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span
                                    class="badge bg-label-danger rounded-pill me-2">+{{ round(($lastWeekUsers / $totalUsers) * 100) }}%</span>
                                <small class="text-muted">vs last week</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-success bg-opacity-10">
                                    <i class="bx bx-user-check fs-4 text-success"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">{{ number_format($activeUsers) }}</h3>
                                <small class="text-muted">Active Users</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span
                                    class="badge bg-label-success rounded-pill me-2">{{ round(($activeUsers / $totalUsers) * 100) }}%</span>
                                <small class="text-muted">of total users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-warning bg-opacity-10">
                                    <i class="bx bx-user-x fs-4 text-warning"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">{{ number_format($pendingUsers) }}</h3>
                                <small class="text-muted">Pending Users</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-warning rounded-pill me-2">Requires action</span>
                                <small class="text-muted">Needs approval</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table with Modern Design -->
        <div class="card">
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom pb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Users List</h4>
                    {{-- <a href="{{ route('admin.users.export') }}" class="btn btn-sm ms-5 btn-success">
                        Export CSV
                    </a> --}}
                </div>
                @if (auth()->user()->hasPermission('user', 'can_create'))
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
                        <i class="fas fa-user-plus me-2"></i> Add New User
                    </button>
                @endif
            </div>
            <div class="card-datatable table-responsive">
                {{-- FILTERS --}}
           <div class="row mb-3 align-items-end">

    <div class="col-md-3">
        <label for="filter-status" class="form-label">Status</label>
        <select id="filter-status" class="form-select">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
            <option value="2">Blocked</option>
        </select>
    </div>

    <div class="col-md-3">
        <label for="filter-from" class="form-label">From Date</label>
        <input type="date" id="filter-from" class="form-control">
    </div>

    <div class="col-md-3">
        <label for="filter-to" class="form-label">To Date</label>
        <input type="date" id="filter-to" class="form-control">
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button id="filter-apply" class="btn btn-primary w-100">
            Apply Filter
        </button>

        <button id="filter-reset" class="btn btn-outline-secondary w-100">
            Reset
        </button>
    </div>

</div>


                <div class="mb-2">
                    <strong>Total Users:</strong> <span id="totalCount">0</span> |
                    <strong>Filtered Users:</strong> <span id="filteredCount">0</span>
                </div>

                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped w-100">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>#</th>
                                <th>Actions</th>

                                @if (Advance_info('register_name', 'yes') === 'yes')
                                    <th>Name</th>
                                @endif

                                @if (Advance_info('register_wallets', 'no') === 'yes')
                                    <th>Wallet</th>
                                @endif

                                <th>Username</th>

                                @if (Advance_info('register_email', 'yes') === 'yes')
                                    <th>Email</th>
                                @endif

                                <th>Sponsor</th>
                                <th>Status</th>
                                <th>Package</th>
                                <th>Registered</th>
                            </tr>
                        </thead>

                    </table>
                </div>

            </div>
        </div>
        <!-- Offcanvas to Add New User -->
        <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvasAddUser"
            aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header border-bottom bg-light">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title fw-bold">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="addNewUserForm" method="POST" action="{{ route('adminusers.create') }}"
                    class="needs-validation" novalidate>
                    @csrf
                    @if (Advance_info('register_name', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label" for="add-user-fullname">Full Name</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="add-user-fullname" name="name"
                                    placeholder="John Doe" required />
                                <div class="invalid-feedback">Please enter full name</div>
                            </div>
                        </div>
                    @endif
                    @if (Advance_info('register_wallets', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label" for="add-user-wallet">Wallet Address</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                                <input type="text" class="form-control" id="add-user-wallet" name="wallet_address"
                                    placeholder="0x..." required />
                                <div class="invalid-feedback">Please enter wallet address</div>
                            </div>
                        </div>
                    @endif
                    @if (Advance_info('register_email', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label" for="add-user-email">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" class="form-control" id="add-user-email" name="email"
                                    placeholder="john@example.com" required />
                                <div class="invalid-feedback">Please enter valid email</div>
                            </div>
                        </div>
                    @endif
                    @if (Advance_info('register_password', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label" for="add-user-password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="add-user-password" name="password"
                                    placeholder="Password" required />
                                <span class="input-group-text toggle-password" style="cursor:pointer"><i
                                        class="bx bx-show"></i></span>
                                <div class="invalid-feedback">Minimum 8 characters required</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-user-password-confirmation">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="add-user-password-confirmation"
                                    name="password_confirmation" placeholder="Confirm Password" required />
                                <span class="input-group-text toggle-password" style="cursor:pointer"><i
                                        class="bx bx-show"></i></span>
                                <div class="invalid-feedback">Passwords must match</div>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" value="user" name="role" class="form-control" />
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="fas fa-user-plus me-2"></i> Create User Account
                    </button>
                </form>
            </div>
        </div>
        <!-- Modal: read-only user detail. Body is fetched from
             adminusers/{id}/view when the View button is pressed. -->
        <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewUserModalBody">
                        {{-- filled by JS --}}
                    </div>
                    <div class="modal-footer">
                        @if (auth()->user()->hasPermission('user', 'can_edit'))
                            <button type="button" class="btn btn-primary" id="viewUserEditBtn">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offcanvas to Edit User -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser"
            aria-labelledby="offcanvasEditUserLabel">
            <div class="offcanvas-header border-bottom">
                <h6 id="offcanvasEditUserLabel" class="offcanvas-title">Edit User</h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="editUserForm" method="POST" action="" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-user-id" name="id" />
                    <input type="hidden" id="edit-user-role" name="role" value="user" />
                    
                    {{-- Name --}}
                    @if (Advance_info('register_name', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="edit-user-name" name="name" class="form-control" required />
                        </div>
                    @endif
                    
                    {{-- Registration-form fields, editable here as well --}}
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Title</label>
                            <select id="edit-user-title" name="title" class="form-select">
                                <option value="">Select</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Dr.">Dr.</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select id="edit-user-gender" name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" id="edit-user-first_name" name="first_name" class="form-control" />
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" id="edit-user-last_name" name="last_name" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unique Name</label>
                        <input type="text" id="edit-user-unique_name" name="unique_name" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" id="edit-user-mobile" name="mobile" class="form-control"
                            inputmode="numeric" placeholder="Mobile No." />
                    </div>

                    <div class="row">
                        {{-- State/city are free text with suggestions: the register form
                             pulls these from an external API, so a hard dropdown here
                             would block editing whenever that API is unreachable. --}}
                        <div class="col-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" id="edit-user-state" name="state" class="form-control"
                                list="edit-state-options" autocomplete="off" />
                            <datalist id="edit-state-options"></datalist>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" id="edit-user-city" name="city" class="form-control"
                                list="edit-city-options" autocomplete="off" />
                            <datalist id="edit-city-options"></datalist>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">PAN</label>
                            <input type="text" id="edit-user-pan" name="pan" class="form-control text-uppercase"
                                maxlength="10" placeholder="ABCDE1234F" />
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Aadhaar</label>
                            <input type="text" id="edit-user-aadhaar" name="aadhaar" class="form-control"
                                maxlength="14" placeholder="1234 5678 9012" />
                        </div>
                    </div>

                    {{-- Wallet --}}
                    @if (Advance_info('register_wallets', 'no') === 'yes')
                        <div class="mb-3">
                            <label class="form-label">Wallet Address (BEP20)</label>
                            <input type="text" id="edit-user-wallet" name="wallet_address" class="form-control"
                                placeholder="0x..." required>
                            <div class="invalid-feedback">Enter valid BEP20 wallet address</div>
                        </div>
                    @endif
                    
                    {{-- Email --}}
                    @if (Advance_info('register_email', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="edit-user-email" name="email" class="form-control" required />
                        </div>
                    @endif
                    
                    {{-- Password (Optional) --}}
                    @if (Advance_info('register_password', 'yes') === 'yes')
                        <div class="mb-3">
                            <label class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="edit-user-password" name="password" class="form-control" placeholder="Enter new password (optional)" />
                                <span class="input-group-text toggle-password-edit" style="cursor:pointer"><i class="bx bx-show"></i></span>
                            </div>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="edit-user-password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm new password" />
                                <span class="input-group-text toggle-password-edit" style="cursor:pointer"><i class="bx bx-show"></i></span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select id="edit-user-status" name="status" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                            <option value="2">Blocked</option>
                        </select>
                    </div>
                    
                    @php
                        $customFields = \App\Models\CustomField::where('module_name', 'register')
                            ->orderBy('position')
                            ->get();
                    @endphp
                    
                    <div id="edit-custom-fields-wrapper">
                        @foreach ($customFields as $field)
                            @php
                                $inputName = "custom[{$field->id}]";
                                $isRequired = $field->required ? 'required' : '';
                                $options = $field->options ? explode(',', $field->options) : [];
                                $lowerName = strtolower($field->name);
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $field->name }}{{ $field->required ? ' *' : '' }}
                                </label>
                                {{-- Phone / WhatsApp --}}
                                @if (str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') || str_contains($lowerName, 'whatsapp'))
                                    <div class="input-group">
                                        <input type="tel" name="{{ $inputName }}" class="form-control authnumber"
                                            {{ $isRequired }}>
                                        <input type="hidden" name="country_code[{{ $field->id }}]">
                                    </div>
                                    {{-- Text / Email / Number / Date --}}
                                @elseif (in_array($field->type, ['text', 'email', 'number', 'date']))
                                    <input type="{{ $field->type }}" name="{{ $inputName }}" class="form-control"
                                        {{ $isRequired }}>
                                    {{-- Select --}}
                                @elseif ($field->type === 'select')
                                    <select name="{{ $inputName }}" class="form-select" {{ $isRequired }}>
                                        <option value="">Select</option>
                                        @foreach ($options as $option)
                                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                        @endforeach
                                    </select>
                                    {{-- Radio --}}
                                @elseif ($field->type === 'radio')
                                    @foreach ($options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="{{ $inputName }}"
                                                value="{{ trim($option) }}">
                                            <label class="form-check-label">{{ trim($option) }}</label>
                                        </div>
                                    @endforeach
                                    {{-- Checkbox --}}
                                @elseif ($field->type === 'checkbox')
                                    @foreach ($options as $index => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="{{ $inputName }}[{{ $index }}]"
                                                value="{{ trim($option) }}">
                                            <label class="form-check-label">{{ trim($option) }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update User</button>
                </form>
            </div>
        </div>
    </div>
    <style>
        /* Modern Card Design */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.375rem rgba(161, 172, 184, 0.12);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(161, 172, 184, 0.15);
            transform: translateY(-2px);
        }

        /* Card Border Shadow Variations */
        .card-border-shadow-primary {
            border-left: 4px solid #7367f0;
        }

        .card-border-shadow-danger {
            border-left: 4px solid #ea5455;
        }

        .card-border-shadow-success {
            border-left: 4px solid #28c76f;
        }

        .card-border-shadow-warning {
            border-left: 4px solid #ff9f43;
        }

        /* Table Styling */
        .table {
            --bs-table-striped-bg: rgba(115, 103, 240, 0.04);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(115, 103, 240, 0.08) !important;
        }

        .table> :not(:last-child)> :last-child>* {
            border-bottom-color: #e9ecef;
        }

        /* Offcanvas Styling */
        .offcanvas {
            box-shadow: -8px 0 20px rgba(34, 41, 47, 0.1);
        }

        /* Input Group Enhancements */
        .input-group-merge .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .input-group-merge .form-control {
            border-left: none;
            padding-left: 0;
        }

        /* Avatar Styling */
        .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Badge Styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.4px;
        }

        /* Toggle Password Eye */
        .toggle-password {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header button {
                margin-top: 1rem;
                width: 100%;
            }
        }
    </style>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        @endif
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll("input[type='tel']");
            inputs.forEach(input => {
                const fieldId = input.id.split('-')[1];
                const countryCodeInput = document.getElementById(`country-code-${fieldId}`);
                const iti = window.intlTelInput(input, {
                    initialCountry: "in",
                    separateDialCode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });
                if (countryCodeInput) {
                    countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
                }
                input.addEventListener("blur", function() {
                    if (countryCodeInput) {
                        countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editRoleSelect = document.getElementById('edit-user-role');
            const editCustomFieldsWrapper = document.getElementById('edit-custom-fields-wrapper');

            function toggleEditCustomFields() {
                const selectedRole = editRoleSelect.value;
                editCustomFieldsWrapper.style.display = selectedRole === 'user' ? 'block' : 'none';
            }
            // Initialize on load
            toggleEditCustomFields();
            // Update on role change
            editRoleSelect.addEventListener('change', toggleEditCustomFields);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('add-user-role');
            const customFieldsWrapper = document.getElementById('custom-fields-wrapper');

            function toggleCustomFields() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'user') {
                    customFieldsWrapper.style.display = 'block';
                } else {
                    customFieldsWrapper.style.display = 'none';
                }
            }
            // Initialize on load
            toggleCustomFields();
            // Update on change
            roleSelect.addEventListener('change', toggleCustomFields);
        });
    </script>



    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>



    <script>

        let table;
        $(document).ready(function() {

         table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,

                pageLength: 25, // ✅ default 25
                lengthMenu: [10, 25, 50, 100], // ✅ dropdown options

                scrollY: '60vh',
                scrollX: true,
                scrollCollapse: true,

                fixedHeader: true,
                responsive: false,

                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"f>>' +
                    '<"row"<"col-12"B>>' +
                    '<"row"<"col-12"tr>>' +
                    '<"row mt-2"<"col-md-5"i><"col-md-7"p>>',

                buttons: [
               
                    {
                        extend: 'excel',
                        text: 'Export Excel (All)',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF (All)',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print (All)',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        text: 'Export Selected',
                        action: function() {
                            exportSelectedRows();
                        }
                    }
                ],

                ajax: {
                    url: "{{ route('adminusers.users.data') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.from_date = $('#filter-from').val();
                        d.to_date = $('#filter-to').val();
                    }
                },

                columnDefs: [{
                    orderable: false,
                    targets: [0, 2]
                }],

                drawCallback: function(settings) {
                    $('#select-all').prop('checked', false);
                    if (settings.json) {
                        $('#totalCount').text(settings.json.totalCount);
                        $('#filteredCount').text(settings.json.filteredCount);
                    }
                }
            });

            $('#filter-apply').on('click', function() {
                table.ajax.reload();
            });

        });
    </script>


    <script>
        $(document).ready(function() {

            // Toggle password visibility for edit form
            $(document).on('click', '.toggle-password-edit', function() {
                const input = $(this).closest('.input-group').find('input');
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bx-hide').addClass('bx-show');
                }
            });

            // State/city suggestions for the edit form. Same free API the public
            // register form uses. Failure is non-fatal: the inputs stay plain
            // text so an admin can always type a value.
            const EDIT_CSC_BASE = "https://countriesnow.space/api/v0.1";
            let editStatesLoaded = false;

            function loadEditStates() {
                if (editStatesLoaded) return;
                editStatesLoaded = true;

                fetch(`${EDIT_CSC_BASE}/countries/states`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ country: "India" })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error || !data.data || !data.data.states) return;
                    const list = document.getElementById('edit-state-options');
                    if (!list) return;
                    list.innerHTML = '';
                    data.data.states.forEach(function(s) {
                        const opt = document.createElement('option');
                        opt.value = s.name;
                        list.appendChild(opt);
                    });
                })
                .catch(() => console.warn('Could not load states for the edit form.'));
            }

            function loadEditCities(state) {
                const list = document.getElementById('edit-city-options');
                if (!list) return;
                list.innerHTML = '';
                if (!state) return;

                fetch(`${EDIT_CSC_BASE}/countries/state/cities`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ country: "India", state: state })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error || !Array.isArray(data.data)) return;
                    data.data.forEach(function(city) {
                        const opt = document.createElement('option');
                        opt.value = city;
                        list.appendChild(opt);
                    });
                })
                .catch(() => console.warn('Could not load cities for the edit form.'));
            }

            $(document).on('change', '#edit-user-state', function() {
                loadEditCities($(this).val());
            });

            // ?edit=<id> opens the edit panel straight away - used by the Edit
            // button on the user detail page.
            const editParam = new URLSearchParams(window.location.search).get('edit');
            if (editParam) {
                openEditUser(editParam);
            }

            $(document).on('click', '.btn-edit', function() {
                openEditUser($(this).data('id'));
            });

            // --- Read-only detail modal -------------------------------------
            let viewUserCurrentId = null;

            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                viewUserCurrentId = id;

                const $body = $('#viewUserModalBody');
                $body.html(
                    '<div class="text-center py-5 text-muted">' +
                    '<div class="spinner-border" role="status"></div>' +
                    '<div class="mt-2">Loading…</div></div>'
                );

                const modalEl = document.getElementById('viewUserModal');
                bootstrap.Modal.getOrCreateInstance(modalEl).show();

                $.ajax({
                    url: `/adminusers/${id}/view`,
                    type: 'GET',
                    success: function(html) {
                        $body.html(html);
                    },
                    error: function(xhr) {
                        $body.html(
                            '<div class="alert alert-danger mb-0">Could not load this user' +
                            (xhr.status ? ' (HTTP ' + xhr.status + ')' : '') + '.</div>'
                        );
                    }
                });
            });

            // Edit from inside the detail modal: close it, then open the editor.
            $(document).on('click', '#viewUserEditBtn', function() {
                if (!viewUserCurrentId) return;
                const id = viewUserCurrentId;
                const modalEl = document.getElementById('viewUserModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                // Wait for the modal to finish closing so the two overlays
                // never fight over the backdrop.
                modalEl.addEventListener('hidden.bs.modal', function handler() {
                    modalEl.removeEventListener('hidden.bs.modal', handler);
                    openEditUser(id);
                });

                modal.hide();
            });

            function openEditUser(id) {
                loadEditStates();
                // Show SweetAlert2 loading dialog
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching user data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });


                $.ajax({
                    url: `/adminusers/${id}/edit`,
                    type: 'GET',
                    success: function(user) {
                        // ✅ FIRST close loading alert
                        Swal.close();
                        // Fill fields
                        $('#edit-user-id').val(user.id);
                        $('#edit-user-name').val(user.name ?? '');
                        $('#edit-user-email').val(user.email ?? '');
                        $('#edit-user-wallet').val(user.wallet_address ?? '');
                        $('#edit-user-status').val(user.status);

                        // Registration-form fields
                        ['title', 'first_name', 'last_name', 'gender', 'state',
                         'city', 'mobile', 'pan', 'aadhaar', 'unique_name'
                        ].forEach(function(field) {
                            $('#edit-user-' + field).val(user[field] ?? '');
                        });
                        if (user.state) loadEditCities(user.state);

                        // Clear password fields
                        $('#edit-user-password').val('');
                        $('#edit-user-password_confirmation').val('');
                        $('#editUserForm').attr('action', `/adminusers/${user.id}`);
                        // Custom fields fill (already correct)
                        const custom = user.custom_fields || {};
                        for (const fieldId in custom) {
                            const value = custom[fieldId];
                            const baseName = `custom[${fieldId}]`;
                            if (Array.isArray(value) || value.includes(',')) {
                                value.toString().split(',').forEach(val => {
                                    $(`input[type=checkbox][name^="${baseName}"][value="${val.trim()}"]`)
                                        .prop('checked', true);
                                });
                                continue;
                            }
                            $(`input[type=radio][name="${baseName}"][value="${value}"]`).prop(
                                'checked', true);
                            $(`[name="${baseName}"]`).val(value);
                        }
                        // ✅ THEN open offcanvas
                        const offcanvasEl = document.getElementById('offcanvasEditUser');
                        const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                        offcanvas.show();
                    },
                    error: function() {
                        Swal.close(); // 🔴 IMPORTANT
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch user data.'
                        });
                    }
                });
            }

            function toggleEditCustomFields() {
                const selectedRole = document.getElementById('edit-user-role').value;
                const wrapper = document.getElementById('edit-custom-fields-wrapper');
                wrapper.style.display = selectedRole === 'user' ? 'block' : 'none';
            }
            document.addEventListener('DOMContentLoaded', function() {
                const editRoleSelect = document.getElementById('edit-user-role');
                editRoleSelect.addEventListener('change', toggleEditCustomFields);
            });
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/adminusers/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                ).then(() => {
                                    // ✅ Reload the whole page after confirmation
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire(
                                    'Failed!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });

            // Select all
    $('#select-all').on('change', function () {
        $('.row-checkbox').prop('checked', this.checked);
    });

function exportSelectedRows() {
    let selectedIds = [];

    $('.row-checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        Swal.fire('No rows selected', 'Please select at least one row', 'warning');
        return;
    }

    // Redirect with IDs
    window.location.href = "{{ route('admin.users.export') }}?ids=" + selectedIds.join(',');
}

$('#filter-reset').on('click', function () {

    $('#filter-status').val('');
    $('#filter-from').val('');
    $('#filter-to').val('');

    $('#select-all').prop('checked', false);
    $('.row-checkbox').prop('checked', false);

    table.ajax.reload(); // ✅ NOW WORKING
});



    </script>
@endsection