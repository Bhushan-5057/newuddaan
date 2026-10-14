@extends('admin.layouts.app')

@section('content')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
.drag-handle {
    cursor: move;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h5 class="card-title mb-0">Add Custom Field</h5>
             @if(auth()->user()->hasPermission('Registion Form', 'can_create'))
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddField">
                Add Custom Fields
            </button>
            @endif
        </div>

        <div class="card-datatable table-responsive p-2">
            <table id="permissionsTable" class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sortableTableBody">
                    @foreach ($fields as $field)
                    <tr data-id="{{ $field->id }}">
                        <td class="drag-handle">
                            <i class="fas fa-arrows-alt"></i>
                        </td>

                        <td>{{ $field->name }}</td>
                        <td>{{ $field->type }}</td>
                        <td>{{ $field->required ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="btn-group">
                                <!-- Edit button -->
                                  @if(auth()->user()->hasPermission('Registion Form', 'can_edit'))
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasEditField{{ $field->id }}">
                                    Edit
                                </button>
                                @endif

                                <!-- Delete button -->
                                @if(auth()->user()->hasPermission('Registion Form', 'can_delete'))
                                <form action="{{ route('admin.custom_fields.destroy', $field->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>

                                @endif
                            </div>
                        </td>

                    </tr>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditField{{ $field->id }}"
                        aria-labelledby="offcanvasEditFieldLabel{{ $field->id }}">
                        <div class="offcanvas-header border-bottom">
                            <h6 id="offcanvasEditFieldLabel{{ $field->id }}" class="offcanvas-title">Edit Field:
                                {{ $field->name }}</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form action="{{ route('admin.custom_fields.update', $field->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Field Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $field->name }}"
                                        required>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="type">Field Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="text" {{ $field->type == 'text' ? 'selected' : '' }}>Text
                                        </option>
                                        <option value="email" {{ $field->type == 'email' ? 'selected' : '' }}>Email
                                        </option>
                                        <option value="number" {{ $field->type == 'number' ? 'selected' : '' }}>Number
                                        </option>
                                        <option value="date" {{ $field->type == 'date' ? 'selected' : '' }}>Date
                                        </option>
                                        <option value="checkbox" {{ $field->type == 'checkbox' ? 'selected' : '' }}>
                                            Checkbox</option>
                                        <option value="select" {{ $field->type == 'select' ? 'selected' : '' }}>Select
                                        </option>
                                        <option value="radio" {{ $field->type == 'radio' ? 'selected' : '' }}>Radio
                                        </option>
                                    </select>
                                </div>

                                <div class="form-check mt-3">
                                    <input type="checkbox" name="required" class="form-check-input"
                                        id="required{{ $field->id }}" {{ $field->required ? 'checked' : '' }}>
                                    <label class="form-check-label" for="required{{ $field->id }}">Required
                                        Field</label>
                                </div>

                                <div class="form-group mt-3" id="optionsWrapperEdit{{ $field->id }}"
                                    style="{{ in_array($field->type, ['select','radio','checkbox']) ? '' : 'display: none;' }}">
                                    <label for="options">Field Options (comma-separated)</label>
                                    <input type="text" name="options" class="form-control"
                                        value="{{ $field->options }}">
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Update</button>
                            </form>
                        </div>
                    </div>


                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Offcanvas to add new user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddField" aria-labelledby="offcanvasAddFieldLabel">
    <div class="offcanvas-header border-bottom">
        <h6 id="offcanvasAddFieldLabel" class="offcanvas-title">Add Custom Field</h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('admin.custom_fields.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Field Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="type">Field Type</label>
                <select name="type" class="form-control" required>
                    <option value="text">Text</option>
                    <option value="email">Email</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                </select>
            </div>

            <div class="form-check mt-3">
                <input type="checkbox" name="required" class="form-check-input" id="required">
                <label class="form-check-label" for="required">Required Field</label>
            </div>
            <div class="form-group mt-3" id="optionsWrapper" style="display: none;">
                <label for="options">Field Options (comma-separated)</label>
                <input type="text" name="options" class="form-control" placeholder="e.g. Male,Female,Other">
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save</button>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- jQuery UI (for sortable) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Attach submit handler to all delete forms
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submit

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the field!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form if confirmed
                }
            });
        });
    });
});
</script>
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
    const typeSelect = document.querySelector('select[name="type"]');
    const optionsWrapper = document.getElementById('optionsWrapper');

    typeSelect.addEventListener('change', function() {
        const selected = this.value;
        if (['select', 'radio', 'checkbox'].includes(selected)) {
            optionsWrapper.style.display = 'block';
        } else {
            optionsWrapper.style.display = 'none';
        }
    });

    typeSelect.dispatchEvent(new Event('change'));
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const handleOptionsToggle = (selectEl, wrapperEl) => {
        const selected = selectEl.value;
        if (['select', 'radio', 'checkbox'].includes(selected)) {
            wrapperEl.style.display = 'block';
        } else {
            wrapperEl.style.display = 'none';
        }
    };

    const mainTypeSelect = document.querySelector('select[name="type"]');
    const mainOptionsWrapper = document.getElementById('optionsWrapper');
    mainTypeSelect.addEventListener('change', () => handleOptionsToggle(mainTypeSelect, mainOptionsWrapper));
    mainTypeSelect.dispatchEvent(new Event('change'));

    @foreach($fields as $field)
    const editTypeSelect {
        {
            $field - > id
        }
    } = document.querySelector('#offcanvasEditField{{ $field->id }} select[name="type"]');
    const editOptionsWrapper {
        {
            $field - > id
        }
    } = document.getElementById('optionsWrapperEdit{{ $field->id }}');

    editTypeSelect {
        {
            $field - > id
        }
    }.addEventListener('change', () => handleOptionsToggle(editTypeSelect {
        {
            $field - > id
        }
    }, editOptionsWrapper {
        {
            $field - > id
        }
    }));
    editTypeSelect {
        {
            $field - > id
        }
    }.dispatchEvent(new Event('change'));
    @endforeach
});
</script>


<script>
$(function() {
    $("#sortableTableBody").sortable({
        update: function(event, ui) {
            let order = [];
            $("#sortableTableBody tr").each(function(index) {
                order.push({
                    id: $(this).data("id"),
                    position: index + 1
                });
            });

            // Send AJAX request
            $.ajax({
                url: "{{ route('admin.custom_fields.reorder') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order: order
                },
                success: function(res) {
                    Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Reordered successfully',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
                },
                error: function(err) {
                     Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Reorder failed',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
                 }); 
                }
            });
        }
    });
});
</script>

@endsection