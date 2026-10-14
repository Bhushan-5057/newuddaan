@extends('superadmin.layouts.app')

@section('content')
<style>
.custom-fields-list {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    background-color: #f9f9f9;
    max-height: 300px;
    overflow-y: auto;
}

.custom-fields-list > div {
    padding: 8px 10px;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

.custom-fields-list > div:last-child {
    border-bottom: none;
}

.custom-fields-list strong {
    color: #333;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">Plan List</h5>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#planCreateModal">
                    Create Plan
                </button>

            </div>
            <div class="card-datatable table-responsive p-2">
                <table id="planTable" class="table datatables-pindetails">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Rank</th>
                            <th>Status</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="planCreateModal" tabindex="-1" aria-labelledby="planCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('superadmin.plan.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planCreateModalLabel">Create Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
        
                <div class="col-md-6 mb-3">
                    <label for="package_name">Package Name</label>
                    <input type="text" class="form-control" name="package_name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="rank">Rank</label>
                    <input type="text" class="form-control" name="rank" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                    {{-- Dynamic Custom Fields --}}
                    @foreach ($customFields as $field)
                        @php
                            $inputName = "custom[{$field->id}]";
                            $isRequired = $field->required ? 'required' : '';
                            $options = $field->options ? explode(',', $field->options) : [];
                            $lowerName = strtolower($field->name);
                        @endphp

                        <div class="col-md-6 mb-3">
                            <label>{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>

                            @if (str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') || str_contains($lowerName, 'whatsapp'))
                                <div class="input-group">
                                    <input type="tel" id="mobile-{{ $field->id }}" name="{{ $inputName }}" class="form-control authnumber" placeholder="Enter number" {{ $isRequired }}>
                                    <input type="hidden" name="country_code[{{ $field->id }}]" id="country-code-{{ $field->id }}">
                                </div>

                            @elseif (in_array($field->type, ['text', 'email', 'number', 'date']))
                                <input type="{{ $field->type }}" name="{{ $inputName }}" class="form-control" {{ $isRequired }}>

                            @elseif ($field->type === 'select')
                                <select name="{{ $inputName }}" class="form-control" {{ $isRequired }}>
                                    <option value="">Select</option>
                                    @foreach ($options as $option)
                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                    @endforeach
                                </select>

                            @elseif ($field->type === 'radio')
                                @foreach ($options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="{{ $inputName }}" value="{{ trim($option) }}" {{ $isRequired }}>
                                        <label class="form-check-label">{{ trim($option) }}</label>
                                    </div>
                                @endforeach

                            @elseif ($field->type === 'checkbox')
                                @foreach ($options as $index => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="{{ $inputName }}[{{ $index }}]" value="{{ trim($option) }}">
                                        <label class="form-check-label">{{ trim($option) }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Canvas Modal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="customFieldCanvas" aria-labelledby="customFieldCanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="customFieldCanvasLabel">Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="customFieldContent">
        <!-- Content will be inserted via JS -->
    </div>
</div>

<div class="modal fade" id="planEditModal" tabindex="-1" aria-labelledby="planEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="" id="planEditForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planEditModalLabel">Edit Pin Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                    <label for="package_name">Package Name</label>
                    <input type="text" class="form-control" name="package_name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="rank">Rank</label>
                    <input type="text" class="form-control" name="rank" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                    {{-- Dynamic Custom Fields (render same as create modal, but prefill values via JS) --}}
                    @foreach ($customFields as $field)
                        @php
                            $inputName = "custom[{$field->id}]";
                            $isRequired = $field->required ? 'required' : '';
                            $options = $field->options ? explode(',', $field->options) : [];
                            $lowerName = strtolower($field->name);
                        @endphp

                        <div class="col-md-6 mb-3">
                            <label>{{ $field->name }}{{ $field->required ? ' *' : '' }}</label>

                            @if (str_contains($lowerName, 'phone') || str_contains($lowerName, 'mobile') || str_contains($lowerName, 'whatsapp'))
                                <div class="input-group">
                                    <input type="tel" id="mobile-edit-{{ $field->id }}" name="{{ $inputName }}" class="form-control authnumber" placeholder="Enter number" {{ $isRequired }}>
                                    <input type="hidden" name="country_code[{{ $field->id }}]" id="country-code-edit-{{ $field->id }}">
                                </div>

                            @elseif (in_array($field->type, ['text', 'email', 'number', 'date']))
                                <input type="{{ $field->type }}" id="field-edit-{{ $field->id }}" name="{{ $inputName }}" class="form-control" {{ $isRequired }}>

                            @elseif ($field->type === 'select')
                                <select name="{{ $inputName }}" id="field-edit-{{ $field->id }}" class="form-control" {{ $isRequired }}>
                                    <option value="">Select</option>
                                    @foreach ($options as $option)
                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                    @endforeach
                                </select>

                            @elseif ($field->type === 'radio')
                                @foreach ($options as $index => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="field-edit-{{ $field->id }}-{{ $index }}" name="{{ $inputName }}" value="{{ trim($option) }}" {{ $isRequired }}>
                                        <label class="form-check-label" for="field-edit-{{ $field->id }}-{{ $index }}">{{ trim($option) }}</label>
                                    </div>
                                @endforeach

                            @elseif ($field->type === 'checkbox')
                                @foreach ($options as $index => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="field-edit-{{ $field->id }}-{{ $index }}" name="{{ $inputName }}[{{ $index }}]" value="{{ trim($option) }}">
                                        <label class="form-check-label" for="field-edit-{{ $field->id }}-{{ $index }}">{{ trim($option) }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script>
function escapeHtml(text) {
    return $('<div>').text(text).html();
}

$(document).on('click', '.view-custom-fields', function() {
    const package_name = $(this).data('package') || 'N/A';
    const rank = $(this).data('rank') ?? 'N/A';
    const status = $(this).data('status') == 1 ? 'Active' : 'Inactive';
    const fields = $(this).data('fields');

    let html = `<p><strong>Package Name:</strong> ${escapeHtml(package_name)}</p>`;
    html += `<p><strong>Rank:</strong> ${escapeHtml(rank)}</p>`;
    html += `<p><strong>Status:</strong> ${status}</p>`;
    
    if (fields && typeof fields === 'object' && Object.keys(fields).length > 0) {
        html += '<div class="custom-fields-list mt-2">';
        for (const [key, value] of Object.entries(fields)) {
            html += `<div class="mb-1"><strong>${escapeHtml(key)}:</strong> ${escapeHtml(value)}</div>`;
        }
        html += '</div>';
    } else {
        html += `<p><em>No custom fields available.</em></p>`;
    }

    $('#customFieldContent').html(html);
    const canvas = new bootstrap.Offcanvas(document.getElementById('customFieldCanvas'));
    canvas.show();
});
</script>


<script>
    $(document).ready(function () {
let table = $('#planTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("superadmin.plan.list") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'package_name', name: 'package_name' },
            { data: 'rank', name: 'rank' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ]
    });
    });



$(document).on('click', '.edit-plan', function() {
    var id = $(this).data('id');
    // Fetch pin detail data via AJAX
    $.ajax({
        url: '/superadmin/plan/' + id + '/edit',
        method: 'GET',
       success: function(response) {
    $('#planEditForm').attr('action', '/superadmin/plan/' + response.id);
    $('#planEditModal input[name="package_name"]').val(response.package_name);
    $('#planEditModal input[name="rank"]').val(response.rank);
    $('#planEditModal select[name="status"]').val(response.status);

    // Prefill dynamic custom fields
    if (response.custom_fields) {
        for (const [fieldId, value] of Object.entries(response.custom_fields)) {
            const fieldName = `custom[${fieldId}]`;

            let $field = $(`#planEditModal [name="${fieldName}"]`);
            if ($field.length) {
                if ($field.is(':checkbox')) {
                    // For checkboxes, value can be an array or single, handle accordingly
                    if (Array.isArray(value)) {
                        $field.each(function() {
                            if (value.includes($(this).val())) {
                                $(this).prop('checked', true);
                            } else {
                                $(this).prop('checked', false);
                            }
                        });
                    } else {
                        $field.prop('checked', $field.val() == value);
                    }
                } else if ($field.is(':radio')) {
                    $field.each(function() {
                        if ($(this).val() == value) {
                            $(this).prop('checked', true);
                        }
                    });
                } else {
                    $field.val(value);
                }
            }
        }
    }

    $('#planEditModal').modal('show');
}

    });
});

$(document).on('click', '.delete-plan', function() {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/superadmin/plan/' + id,
                method: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Plan has been deleted.',
                        'success'
                    );
                    $('#planTable').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Failed to delete Plan.',
                        'error'
                    );
                }
            });
        }
    });
});

</script>


@endsection