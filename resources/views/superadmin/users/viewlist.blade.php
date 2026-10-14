@extends('superadmin.layouts.app')





@section('content')


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row g-4 mb-4">

        <div class="col-sm-6 col-xl-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between">

                        <div class="content-left">

                            <span>Total Users</span>

                            <div class="d-flex align-items-end mt-2">

                                <h4 class="mb-0 me-2">{{ $totalUsers }}</h4>

                                <small class="text-success mb-0">(+29%)</small>

                            </div>

                            <small>Total Users</small>

                        </div>

                        <div class="avatar">

                            <span class="avatar-initial rounded bg-label-primary">

                                <i class="bx bx-user bx-sm"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-sm-6 col-xl-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between">

                        <div class="content-left">

                            <span>Last week Users</span>

                            <div class="d-flex align-items-end mt-2">

                                <h4 class="mb-0 me-2">{{ $lastWeekUsers }}</h4>

                                <small class="text-success mb-0">(+18%)</small>

                            </div>

                            <small>Last week analytics </small>

                        </div>

                        <div class="avatar">

                            <span class="avatar-initial rounded bg-label-danger">

                                <i class="bx bx-user-check bx-sm"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-sm-6 col-xl-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between">

                        <div class="content-left">

                            <span>Active Users</span>

                            <div class="d-flex align-items-end mt-2">

                                <h4 class="mb-0 me-2">{{ $activeUsers }}</h4>

                                <small class="text-danger mb-0">(-14%)</small>

                            </div>

                            <small>Last week analytics</small>

                        </div>

                        <div class="avatar">

                            <span class="avatar-initial rounded bg-label-success">

                                <i class="bx bx-group bx-sm"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-sm-6 col-xl-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between">

                        <div class="content-left">

                            <span>Pending Users</span>

                            <div class="d-flex align-items-end mt-2">

                                <h4 class="mb-0 me-2">{{ $pendingUsers }}</h4>

                                <small class="text-success mb-0">(+42%)</small>

                            </div>

                            <small>Last week analytics</small>

                        </div>

                        <div class="avatar">

                            <span class="avatar-initial rounded bg-label-warning">

                                <i class="bx bx-user-voice bx-sm"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Users List Table -->

    <div class="card p-3">

        <div class="card-header d-flex justify-content-between align-items-center border-bottom">

            <h5 class="card-title mb-0">Users List</h5>

            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">

                <i class="fas fa-user-plus me-1"></i> Add New User

            </button>

        </div>



        <div class="card-datatable table-responsive p-2">



            <table id="userTable" class="table ">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Name</th>

                        <th>Username</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Sponsor</th>

                        <th>Status</th>
                        
                        <th>Package</th>

                        <th>Created At</th>

                        <th class="no-sort">Action</th>

                    </tr>

                </thead>

            </table>

        </div>

        <!-- Offcanvas to add new user -->

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"

            aria-labelledby="offcanvasAddUserLabel">

            <div class="offcanvas-header border-bottom">

                <h6 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h6>

                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"

                    aria-label="Close"></button>

            </div>

            <div class="offcanvas-body">

                <form id="addNewUserForm" method="POST" action="{{ route('superadminusers.create') }}">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label" for="add-user-fullname">Full Name</label>

                        <input type="text" class="form-control" id="add-user-fullname" name="name"

                            placeholder="John Doe" required />

                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="add-user-email">Email</label>

                        <input type="email" class="form-control" id="add-user-email" name="email"

                            placeholder="john@example.com" required />

                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="add-user-password">Password</label>

                        <input type="password" class="form-control" id="add-user-password" name="password"

                          placeholder="Leave blank to keep current password"   required />

                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="add-user-password-confirmation">Confirm Password</label>

                        <input type="password" class="form-control" id="add-user-password-confirmation"

                            name="password_confirmation"  required />

                    </div>

                    @php

                    $customFields = \App\Models\CustomField::where('module_name' , 'register')->orderBy('position')->get(); 

                    @endphp

                    <div id="custom-fields-wrapper">

                        @foreach ($customFields as $field)

                        @php

                        $inputName = "custom[{$field->id}]";

                        $isRequired = $field->required ? 'required' : '';

                        $options = $field->options ? explode(',', $field->options) : [];

                        $lowerName = strtolower($field->name);

                        @endphp



                        <div class="mb-3">

                            <label class="form-label">{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>



                            {{-- Phone/WhatsApp Field --}}

                            @if (str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') ||

                            str_contains($lowerName, 'whatsapp'))

                            <div class="input-group">

                                <input type="tel" id="mobile-{{ $field->id }}" name="{{ $inputName }}"

                                    class="form-control authnumber" placeholder="Enter number" {{ $isRequired }}>

                                <input type="hidden" name="country_code[{{ $field->id }}]"

                                    id="country-code-{{ $field->id }}">

                            </div>



                            {{-- Standard input --}}

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

                                    value="{{ trim($option) }}" {{ $isRequired }}>

                                <label class="form-check-label">{{ trim($option) }}</label>

                            </div>

                            @endforeach



                            {{-- Checkbox --}}

                            @elseif ($field->type === 'checkbox')

                            @foreach ($options as $index => $option)

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="{{ $inputName }}[{{ $index }}]"

                                    value="{{ trim($option) }}">

                                <label class="form-check-label">{{ trim($option) }}</label>

                            </div>

                            @endforeach

                            @endif

                        </div>

                        @endforeach

                    </div>

                    <div class="mb-4">

                        <label class="form-label" for="add-user-role">User Role</label>

                        <select id="add-user-role" name="role" class="form-select" required>

                            <option value="">Select Role</option>

                            <option value="super admin">Super Admin</option>

                            <option value="admin">Admin</option>

                            <option value="user" selected>User</option> {{-- default selected --}}



                        </select>

                    </div>

                    <button type="submit" class="btn btn-primary w-100">Create User</button>

                </form>

            </div>

        </div>



        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser"

            aria-labelledby="offcanvasEditUserLabel">

            <div class="offcanvas-header border-bottom">

                <h6 id="offcanvasEditUserLabel" class="offcanvas-title">Edit User</h6>

                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"

                    aria-label="Close"></button>

            </div>

            <div class="offcanvas-body">

                <form id="editUserForm" method="POST" action="">



                    @csrf

                    @method('PUT')

                    <input type="hidden" id="edit-user-id" name="id" />



                    <div class="mb-3">

                        <label for="edit-user-name" class="form-label">Full Name</label>

                        <input type="text" id="edit-user-name" name="name" class="form-control" required />

                    </div>



                    <div class="mb-3">

                        <label for="edit-user-email" class="form-label">Email</label>

                        <input type="email" id="edit-user-email" name="email" class="form-control" required />

                    </div>



                    <div class="mb-4">

                        <label for="edit-user-role" class="form-label">Role</label>

                        <select id="edit-user-role" name="role" class="form-select" required>

                            <option value="super admin">Super Admin</option>

                            <option value="admin">Admin</option>

                            <option value="user">User</option>

                        </select>

                    </div>
<div class="mb-3">
    <label for="edit-user-password" class="form-label">New Password</label>
    <input type="password" id="edit-user-password" name="password" class="form-control" placeholder="Leave blank to keep current password">
</div>

<div class="mb-3">
    <label for="edit-user-password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" id="edit-user-password_confirmation" name="password_confirmation" class="form-control">
</div>

    <div class="mb-3">
        <label for="edit-user-status" class="form-label">Status</label>
        <select id="edit-user-status" name="status" class="form-select" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
            <option value="2">Blocked</option>
        </select>
    </div>
                    @php

                    $customFields = \App\Models\CustomField::where('module_name' , 'register')->orderBy('position')->get(); 

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

                            <label class="form-label">{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>



                            {{-- Phone/WhatsApp Field --}}

                            @if (str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') ||

                            str_contains($lowerName, 'whatsapp'))

                            <div class="input-group">

                                <input type="tel" name="{{ $inputName }}" class="form-control authnumber"

                                    placeholder="Enter number" {{ $isRequired }}>

                                <input type="hidden" name="country_code[{{ $field->id }}]">

                            </div>



                            {{-- Standard input --}}

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

                                    value="{{ trim($option) }}" {{ $isRequired }}>

                                <label class="form-check-label">{{ trim($option) }}</label>

                            </div>

                            @endforeach



                            {{-- Checkbox --}}

                            @elseif ($field->type === 'checkbox')

                            @foreach ($options as $index => $option)

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="{{ $inputName }}[{{ $index }}]"

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

</div>









@endsection



@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>



<script>

        @if(session('success'))

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



    @if($errors->any())

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



<script>

$(document).ready(function() {

    let table = $('#userTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('superadminusers.users.data') }}",

        columnDefs: [{

            targets: 'no-sort',

            orderable: false

        }]

    });





   $(document).on('click', '.btn-edit', function () {

    const id = $(this).data('id');



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

        url: `/superadminusers/${id}/edit`,

        type: 'GET',

        success: function (user) {

            Swal.close(); // Close loading dialog



            // Fill basic user fields

            $('#edit-user-id').val(user.id);

            $('#edit-user-name').val(user.name);

            $('#edit-user-email').val(user.email);

            $('#edit-user-role').val(user.role);
                $('#edit-user-status').val(user.status);



            // Set form action

            $('#editUserForm').attr('action', `/superadminusers/${user.id}`);



            // Handle custom fields

            const custom = user.custom_fields || {};

            for (const fieldId in custom) {

                const value = custom[fieldId];

                const baseName = `custom[${fieldId}]`;



                // Handle checkboxes

                if (Array.isArray(value) || (typeof value === 'string' && value.includes(','))) {

                    const values = Array.isArray(value) ? value : value.split(',');

                    values.forEach(val => {

                        $(`input[type=checkbox][name^="${baseName}"]`).each(function () {

                            if ($(this).val().trim() === val.trim()) {

                                $(this).prop('checked', true);

                            }

                        });

                    });

                    continue;

                }



                // Handle radios

                $(`input[type=radio][name="${baseName}"][value="${value}"]`).prop('checked', true);



                // Handle select and text/email/date/number

                const $field = $(`[name="${baseName}"]`);

                if ($field.length && !$field.is(':radio') && !$field.is(':checkbox')) {

                    $field.val(value);

                }

            }



            // Trigger custom field UI updates if needed

            toggleEditCustomFields();



            // Show the offcanvas

            const editOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasEditUser'));

            editOffcanvas.show();

        },

        error: function () {

            Swal.fire({

                icon: 'error',

                title: 'Error',

                text: 'Failed to fetch user data. Please try again.',

            });

        }

    });

});







    function toggleEditCustomFields() {

        const selectedRole = document.getElementById('edit-user-role').value;

        const wrapper = document.getElementById('edit-custom-fields-wrapper');

        wrapper.style.display = selectedRole === 'user' ? 'block' : 'none';

    }



    document.addEventListener('DOMContentLoaded', function() {

        const editRoleSelect = document.getElementById('edit-user-role');

        editRoleSelect.addEventListener('change', toggleEditCustomFields);

    });





$(document).on('click', '.btn-delete', function () {

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

                url: `/superadminusers/${id}`,

                type: 'DELETE',

                data: {

                    _token: '{{ csrf_token() }}'

                },

                success: function () {

                    Swal.fire(

                        'Deleted!',

                        'User has been deleted.',

                        'success'

                    ).then(() => {

                        // ✅ Reload the whole page after confirmation

                        location.reload();

                    });

                },

                error: function () {

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

</script>

@endsection