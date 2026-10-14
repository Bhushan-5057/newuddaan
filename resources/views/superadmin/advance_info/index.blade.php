@extends('superadmin.layouts.app')



@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .dataTables_wrapper .row:first-child,
        .dataTables_wrapper .row:last-child {
            padding: 10px 0;
        }
        .nav-tabs {
    scroll-behavior: smooth;
}

    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Advance Info Management</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Info</button>

                </div>


                {{-- Dynamic Tabs --}}
          <div style="overflow-x: auto; white-space: nowrap;">
    <ul class="nav nav-tabs mb-3 flex-nowrap" id="myTab" role="tablist" style="min-width: max-content;">
        @foreach ($titles as $index => $title)
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ strtolower($title) }}"
                    data-title="{{ $title }}" type="button" role="tab">
                    {{ ucfirst($title) }}
                </button>
            </li>
        @endforeach
    </ul>
</div>

                <input type="hidden" id="filterTitle" value="">



                <div class="card-datatable table-responsive p-2">
                    <table class="table table-bordered table-striped" id="advanceInfoTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Value</th>
                                <th>Type</th>
                                <th>Options</th>
                                <th>Status</th>
                                <th>Admin Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Advance Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="addTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="addTitle" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="addName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addLabel" class="form-label">Label</label>
                                    <input type="text" class="form-control" id="addLabel" name="label" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addType" class="form-label">Type</label>
                                    <select class="form-select" id="addType" name="type" required>
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="option">Option (Single Select)</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="days">Days (Multi-select)</option>
                                        <option value="array">Array (Multi-select from Options)</option>
                                        <option value="json_array">JSON Array (Checkboxes from Options)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="add-options" class="form-label">Options (JSON or comma-separated)</label>
                                    <textarea name="options" class="form-control" id="add-options" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="addValueInput" class="form-label">Value</label>
                                    <div id="addValueInput">
                                        {{-- Dynamic input will be rendered here --}}
                                        <input type="text" class="form-control" name="value" value="" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="addStatus" class="form-label">Status</label>
                                    <select class="form-select" id="addStatus" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="addAdminStatus" class="form-label">Admin Status</label>
                                    <select class="form-select" id="addAdminStatus" name="admin_status" required>
                                        <option value="1">Approved</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Advance Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="modal-body">
                                <input type="hidden" id="editId" name="id">
                                <div class="mb-3">
                                    <label for="editTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="editTitle" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editLabel" class="form-label">Label</label>
                                    <input type="text" class="form-control" id="editLabel" name="label" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editType" class="form-label">Type</label>
                                    <select class="form-select" id="editType" name="type" required>
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="option">Option (Single Select)</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="days">Days (Multi-select)</option>
                                        <option value="array">Array (Multi-select from Options)</option>
                                        <option value="json_array">JSON Array (Checkboxes from Options)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-options" class="form-label">Options (JSON or comma-separated)</label>
                                    <textarea name="options" class="form-control" id="edit-options" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editValueInput" class="form-label">Value</label>
                                    <div id="editValueInput">
                                        {{-- Dynamic input will be rendered here --}}
                                        <input type="text" class="form-control" name="value" value="" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStatus" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editAdminStatus" class="form-label">Admin Status</label>
                                    <select class="form-select" id="editAdminStatus" name="admin_status" required>
                                        <option value="1">Approved</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@endsection

        @section('scripts') {{-- Using @section for scripts which will yield into the main layout's @stack('scripts')
            --}}
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            <script>
                $('#addModal').on('hidden.bs.modal', function () {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});

                // Function to render dynamic value input fields based on type
                function renderDynamicValueInput(type, value, options, targetElement) {
                    let inputHtml = '';
                    targetElement.empty(); // Clear existing content

                    let parsedOptions = {};
                    if (options && typeof options === 'string') {
                        if (options.trim().startsWith('{') && options.trim().endsWith('}')) {
                            try {
                                parsedOptions = JSON.parse(options);
                            } catch (e) {
                                console.error('Error parsing options JSON:', e, 'Options received:', options);
                                parsedOptions = {};
                            }
                        } else if (options.includes(',')) {
                            // Handle comma-separated options for 'option' and 'array' types
                            options.split(',').forEach(opt => {
                                const trimmedOpt = opt.trim();
                                if (trimmedOpt) {
                                    parsedOptions[trimmedOpt] = trimmedOpt; // Key and value are the same
                                }
                            });
                        }
                    } else if (typeof options === 'object' && options !== null) {
                        // If options is already an object (e.g., from server response)
                        parsedOptions = options;
                    }

                    let parsedValue;
                    // Attempt to parse value if it looks like JSON
                    if (value && typeof value === 'string' && (value.trim().startsWith('{') || value.trim().startsWith('['))) {
                        try {
                            parsedValue = JSON.parse(value);
                        } catch (e) {
                            console.error('Error parsing value JSON:', e, 'Value received:', value);
                            parsedValue = value; // Keep as string if parsing fails
                        }
                    } else if (typeof value === 'object' && value !== null) {
                        parsedValue = value; // Value is already an object/array
                    } else {
                        parsedValue = value; // Keep as is (string, number, boolean)
                    }

                    switch (type) {
                        case 'text':
                            inputHtml = `<input type="text" class="form-control" name="value" value="${parsedValue || ''}" required>`;
                            break;
                        case 'number':
                            inputHtml = `<input type="number" class="form-control" name="value" value="${parsedValue || ''}" required>`;
                            break;
                        case 'option':
                            inputHtml = `<select class="form-select" name="value" required>`;
                            inputHtml += `<option value="">Select an option</option>`;
                            if (Object.keys(parsedOptions).length > 0) {
                                for (const key in parsedOptions) {
                                    const optionValue = key;
                                    const optionLabel = parsedOptions[key];
                                    const selected = (parsedValue == optionValue) ? 'selected' : '';
                                    inputHtml += `<option value="${optionValue}" ${selected}>${optionLabel}</option>`;
                                }
                            }
                            inputHtml += `</select>`;
                            break;
                        case 'checkbox':
                            // Convert value to boolean for checkbox state
                            const isChecked = (parsedValue === '1' || parsedValue === true || parsedValue === 1);
                            inputHtml = `<div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="value" value="1" id="${targetElement.attr('id')}Checkbox" ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label" for="${targetElement.attr('id')}Checkbox">Enabled</label>
                                    </div>`;
                            break;
                        case 'days':
                            // Add a hidden input to indicate this is a days type for server-side processing
                            inputHtml = `<input type="hidden" name="is_days_type" value="1">`;
                            inputHtml += `<select class="form-select" name="value[]" multiple required>`;
                            const allDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            const selectedDays = Array.isArray(parsedValue) ? parsedValue : (typeof parsedValue === 'string' ? parsedValue.split(',').map(s => s.trim()) : []);
                            allDays.forEach(day => {
                                const selected = selectedDays.includes(day) ? 'selected' : '';
                                inputHtml += `<option value="${day}" ${selected}>${day}</option>`;
                            });
                            inputHtml += `</select>`;
                            break;
                        case 'array':
                            // Add a hidden input to indicate this is an array type for server-side processing
                            inputHtml = `<input type="hidden" name="is_array_type" value="1">`;
                            inputHtml += `<select class="form-select" name="value[]" multiple required>`;
                            const currentSelectedArray = Array.isArray(parsedValue) ? parsedValue : []; // Ensure it's an array
                            if (Object.keys(parsedOptions).length > 0) {
                                for (const key in parsedOptions) {
                                    const optionValue = key;
                                    const optionLabel = parsedOptions[key];
                                    // Check if the current option's value is in the selected array
                                    const selected = currentSelectedArray.includes(optionValue) ? 'selected' : '';
                                    inputHtml += `<option value="${optionValue}" ${selected}>${optionLabel}</option>`;
                                }
                            }
                            inputHtml += `</select>`;
                            break;
                        case 'json_array':
                            // Add a hidden input to indicate this is a JSON array type for server-side processing
                            inputHtml = `<input type="hidden" name="is_json_array_type" value="1">`;
                            if (Object.keys(parsedOptions).length > 0) {
                                inputHtml += `<div class="p-2 border rounded">`;
                                const currentJsonArrayValues = typeof parsedValue === 'object' && parsedValue !== null ? parsedValue : {};
                                for (const key in parsedOptions) {
                                    const checkboxValue = key;
                                    const checkboxLabel = parsedOptions[key];
                                    // Check if the checkbox value is present and its value is 1 in the parsed JSON object
                                    const isChecked = currentJsonArrayValues.hasOwnProperty(checkboxValue) && (currentJsonArrayValues[checkboxValue] == 1 || currentJsonArrayValues[checkboxValue] === true);
                                    inputHtml += `<div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="value[${checkboxValue}]" value="1" id="${targetElement.attr('id')}-${checkboxValue}" ${isChecked ? 'checked' : ''}>
                                                <label class="form-check-label" for="${targetElement.attr('id')}-${checkboxValue}">${checkboxLabel}</label>
                                            </div>`;
                                }
                                inputHtml += `</div>`;
                            } else {
                                inputHtml = `<p>No options defined for this JSON array type.</p>`;
                            }
                            break;
                        default:
                            inputHtml = `<input type="text" class="form-control" name="value" value="${parsedValue || ''}" required>`;
                            break;
                    }
                    targetElement.html(inputHtml);
                }

                $(document).ready(function () {
                    // CSRF token for AJAX requests
                    let selectedTitle = 'all';

                    const table = $('#advanceInfoTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route("superadmin.advanceinfo.data") }}',
                            data: function (d) {
                                d.title = selectedTitle;
                            }
                        },
                        columns: [
                            { data: 'id' },
                            { data: 'title' },
                            { data: 'name' },
                            { data: 'label' },
                            { data: 'value' },
                            { data: 'type' },
                            { data: 'options' },
                            { data: 'status', orderable: false, searchable: false },
                            { data: 'admin_status', orderable: false, searchable: false },
                            { data: 'created_at' },
                            { data: 'action', orderable: false, searchable: false }
                        ]
                    });

                    $(document).on('click', '.nav-link', function () {
                        selectedTitle = $(this).data('title') || 'all';
                        table.ajax.reload();
                    });

                    // Add Form Submission
                    $('#addForm').on('submit', function (e) {
                        e.preventDefault();
                        var formData = $(this).serializeArray();
                        var dataToSend = {};

                        // Manually process form data to handle arrays and nested objects (for json_array)
                        formData.forEach(function (item) {
                            if (item.name.endsWith('[]')) { // Handle multi-select arrays (days, array)
                                var name = item.name.slice(0, -2);
                                if (!dataToSend[name]) {
                                    dataToSend[name] = [];
                                }
                                dataToSend[name].push(item.value);
                            } else if (item.name.startsWith('value[')) { // Handle json_array checkboxes
                                var match = item.name.match(/^value\[(.*)\]$/);
                                if (match) {
                                    if (!dataToSend['value']) {
                                        dataToSend['value'] = {};
                                    }
                                    dataToSend['value'][match[1]] = item.value;
                                }
                            } else {
                                dataToSend[item.name] = item.value;
                            }
                        });

                        // Special handling for checkbox type (if unchecked, it's not serialized)
                        if (dataToSend.type === 'checkbox') {
                            dataToSend.value = $('#addValueInput').find('input[name="value"]').is(':checked') ? '1' : '0';
                        }

                        // Convert array/object values to JSON string for storage if needed
                        if (dataToSend.type === 'days' || dataToSend.type === 'array' || dataToSend.type === 'json_array') {
                            if (typeof dataToSend.value === 'object' && dataToSend.value !== null) {
                                dataToSend.value = JSON.stringify(dataToSend.value);
                            }
                        }
                        // Handle options field, if not empty and looks like JSON, stringify it
                        if (dataToSend.options && dataToSend.options.trim().startsWith('{') && dataToSend.options.trim().endsWith('}')) {
                            try {
                                JSON.parse(dataToSend.options); // Test if it's valid JSON
                                // If it's valid, keep it as is (it's already a string)
                            } catch (e) {
                                // Not valid JSON, might be comma separated, keep as is
                            }
                        }

                        $.ajax({
                            url: "{{ route('superadmin.advanceinfo.store') }}",
                            method: 'POST',
                            data: dataToSend,
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#addModal').modal('hide');
                                    $('#addForm')[0].reset();
                                    // Reset dynamic input to default (text) after successful add
                                    renderDynamicValueInput('text', '', '', $('#addValueInput'));
                                    table.draw(); // Reload DataTable
                                } else {
                                    toastr.error('Error: ' + JSON.stringify(response.errors));
                                }
                            },
                            error: function (xhr) {
                                let errors = xhr.responseJSON.errors;
                                if (errors) {
                                    for (let key in errors) {
                                        toastr.error(errors[key][0]);
                                    }
                                } else {
                                    toastr.error('An error occurred. Please try again.');
                                }
                            }
                        });
                    });

                    // Edit Button Click - Fetch data and populate modal
                    $(document).on('click', '.btn-edit', function () {
                        var id = $(this).data('id');

                        // Clear previous validation errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        $.ajax({
                            url: "{{ url('superadmin/advance-info/edit') }}/" + id, // Correct GET route for fetching data
                            method: 'GET',
                            success: function (response) {
                                $('#editId').val(response.id);
                                $('#editTitle').val(response.title);
                                $('#editName').val(response.name);
                                $('#editLabel').val(response.label);
                                $('#editType').val(response.type);
                                $('#editStatus').val(response.status ? '1' : '0');
                                $('#editAdminStatus').val(response.admin_status ? '1' : '0');

                                // Ensure options are a string for the textarea, even if received as object
                                $('#edit-options').val(typeof response.options === 'object' && response.options !== null ? JSON.stringify(response.options) : response.options || '');

                                // Render the dynamic value input based on the fetched type, value, and options
                                renderDynamicValueInput(response.type, response.value, $('#edit-options').val(), $('#editValueInput'));

                                $('#editModal').modal('show');
                            },
                            error: function (xhr) {
                                console.error("Error fetching advance info details:", xhr);
                                toastr.error('Failed to load advance info details. Please try again.');
                            }
                        });
                    });

                    // On change of Type in Edit Modal, re-render the value input
                    $('#editType').on('change', function () {
                        const selectedType = $(this).val();
                        const currentOptions = $('#edit-options').val(); // Use the options from the textarea
                        // When type changes, clear value and re-render based on new type and current options
                        renderDynamicValueInput(selectedType, '', currentOptions, $('#editValueInput'));
                    });

                    // Edit Form Submission
                    $('#editForm').on('submit', function (e) {
                        e.preventDefault();
                        var id = $('#editId').val();
                        var formData = $(this).serializeArray();
                        var dataToSend = {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT', // Spoof the PUT method
                        };

                        // Manually process form data to handle arrays and nested objects (for json_array)
                        formData.forEach(function (item) {
                            if (item.name.endsWith('[]')) {
                                var name = item.name.slice(0, -2);
                                if (!dataToSend[name]) {
                                    dataToSend[name] = [];
                                }
                                dataToSend[name].push(item.value);
                            } else if (item.name.startsWith('value[')) {
                                var match = item.name.match(/^value\[(.*)\]$/);
                                if (match) {
                                    if (!dataToSend['value']) {
                                        dataToSend['value'] = {};
                                    }
                                    dataToSend['value'][match[1]] = item.value;
                                }
                            } else {
                                dataToSend[item.name] = item.value;
                            }
                        });

                        if (dataToSend.type === 'checkbox') {
                            dataToSend.value = $('#editValueInput').find('input[name="value"]').is(':checked') ? '1' : '0';
                        }

                        if (['days', 'array', 'json_array'].includes(dataToSend.type)) {
                            if (typeof dataToSend.value === 'object' && dataToSend.value !== null) {
                                dataToSend.value = JSON.stringify(dataToSend.value);
                            }
                        }

                        if (dataToSend.options && dataToSend.options.trim().startsWith('{') && dataToSend.options.trim().endsWith('}')) {
                            try {
                                JSON.parse(dataToSend.options); // keep as-is if valid JSON
                            } catch (e) {
                                // invalid JSON, do nothing (fallback)
                            }
                        }

                        $.ajax({
                            url: '/superadmin/advance-info/update/' + id,
                            type: 'POST', // Use POST method to simulate PUT
                            data: {
                                _token: $('input[name="_token"]').val(),
                                _method: 'PUT', // Laravel will recognize this as a PUT request
                                ...dataToSend
                            },
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#editModal').modal('hide');
                                    table.draw(); // Redraw DataTable
                                } else {
                                    toastr.error('Something went wrong');
                                }
                            },
                            error: function (xhr) {
                                let errors = xhr.responseJSON?.errors;
                                if (errors) {
                                    for (let key in errors) {
                                        toastr.error(errors[key][0]);
                                    }
                                } else {
                                    toastr.error('Server error, check logs');
                                }
                            }
                        });

                    });



                    // Initial render for the add form's value input on page load
                    $('#addType').on('change', function () {
                        const selectedType = $(this).val();
                        const currentOptions = $('#add-options').val(); // Use the options from the textarea
                        renderDynamicValueInput(selectedType, '', currentOptions, $('#addValueInput'));
                    }).change(); // Trigger change on load for add form
                });

                $(document).on('click', '.btn-delete', function () {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/superadmin/advance-info/delete/' + id,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // <-- Reload page after success
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log("Response Text:", xhr.responseText);
                    Swal.fire(
                        'Oops!',
                        'Something went wrong. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});




            </script>
        @endsection