@extends('superadmin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
.drag-handle {
    cursor: move;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h5 class="card-title mb-0">Add Custom Field Plan</h5>
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddField">
                Add Custom Fields
            </button>
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
                        <td class="drag-handle"><i class="fas fa-arrows-alt"></i></td>
                        <td>{{ $field->name }}</td>
                        <td>{{ ucfirst($field->type) }}</td>
                        <td>{{ $field->required ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasEditField{{ $field->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('plan.custom_fields.destroy', $field->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Offcanvas -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditField{{ $field->id }}">
                        <div class="offcanvas-header border-bottom">
                            <h6 class="offcanvas-title">Edit Field: {{ $field->name }}</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form action="{{ route('plan.custom_fields.update', $field->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Field Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $field->name }}" required>
                                </div>

                                <div class="form-group mt-3">
                                    <label>Field Type</label>
                                    <select name="type" class="form-control edit-type-select" data-target="optionsWrapperEdit{{ $field->id }}">
                                        @foreach(['text','email','number','date','checkbox','select','radio'] as $type)
                                            <option value="{{ $type }}" {{ $field->type == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check mt-3">
                                    <input type="checkbox" name="required" class="form-check-input" id="required{{ $field->id }}" {{ $field->required ? 'checked' : '' }}>
                                    <label class="form-check-label" for="required{{ $field->id }}">Required Field</label>
                                </div>

                                <div class="form-group mt-3 options-wrapper" id="optionsWrapperEdit{{ $field->id }}" style="{{ in_array($field->type, ['select','radio','checkbox']) ? '' : 'display:none;' }}">
                                    <label>Field Options (comma-separated)</label>
                                    <input type="text" name="options" class="form-control" value="{{ $field->options }}">
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

<!-- Add Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddField">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title">Add Custom Field</h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('plan.custom_fields.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Field Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label>Field Type</label>
                <select name="type" class="form-control" id="addTypeSelect">
                    @foreach(['text','email','number','date','checkbox','select','radio'] as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mt-3">
                <input type="checkbox" name="required" class="form-check-input" id="required">
                <label class="form-check-label" for="required">Required Field</label>
            </div>

            <div class="form-group mt-3 options-wrapper" id="optionsWrapper" style="display:none;">
                <label>Field Options (comma-separated)</label>
                <input type="text" name="options" class="form-control" placeholder="e.g. Male,Female,Other">
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function () {
    $('#permissionsTable').DataTable();

    // Reorder
    $("#sortableTableBody").sortable({
        handle: '.drag-handle',
        update: function () {
            let order = [];
            $("#sortableTableBody tr").each(function (index) {
                order.push({ id: $(this).data("id"), position: index + 1 });
            });
            $.post("{{ route('plan.custom_fields.reorder') }}", {
                _token: "{{ csrf_token() }}",
                order: order
            }).done(() => {
                Swal.fire({ icon: 'success', title: 'Reordered successfully', toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000 });
            }).fail(() => {
                Swal.fire({ icon: 'error', title: 'Reorder failed', toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000 });
            });
        }
    });

    // Delete confirmation
    $('.delete-form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the field!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Show/hide options field
    function toggleOptions(selectEl, targetId) {
        const val = $(selectEl).val();
        const show = ['select', 'radio', 'checkbox'].includes(val);
        $(`#${targetId}`).toggle(show);
    }

    $('#addTypeSelect').on('change', function () {
        toggleOptions(this, 'optionsWrapper');
    }).trigger('change');

    $('.edit-type-select').each(function () {
        const targetId = $(this).data('target');
        $(this).on('change', function () {
            toggleOptions(this, targetId);
        }).trigger('change');
    });

    // Flash success
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000
    });
    @endif

    // Validation errors
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
    @endif
});
</script>
@endsection
