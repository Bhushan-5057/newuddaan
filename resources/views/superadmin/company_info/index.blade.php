@extends('superadmin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">Company info</h5>


                </div>
                <div class="card-datatable table-responsive p-2">
                    <table id="companyInfoTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Label</th>
                                <th>Value</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Admin Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Offcanvas Modal -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editModal">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Edit Company Info</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('superadmin.companyinfo.update') }}">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="offcanvas-body">
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title" id="edit-title" required>
                </div>
                <div class="mb-3">
                    <label>Label</label>
                    <input type="text" class="form-control" name="label" id="edit-label" required>
                </div>
                <div class="mb-3">
                    <label>Type</label>
                    <select class="form-control" name="type" id="edit-type" required>
                        <option value="text">Text</option>
                        <option value="file">File</option>
                        <option value="select">Select</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit-status">Status</label>
<select name="status" id="edit-status" class="form-select" required>
    <option value="1">Active</option>
    <option value="0">Inactive</option>
</select>

                </div>
                <div class="mb-3">
                    <label for="edit-admin-status">Admin Status</label>
<select name="admin_status" id="edit-admin-status" class="form-select" required>
    <option value="1">Active</option>
    <option value="0">Pending</option>
</select>
                </div>
                <div class="mb-3">
                    <label>Value</label>
                    <div id="edit-value-wrapper"></div>
                </div>
            </div>
            <div class="offcanvas-footer px-3 pb-3">
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<!-- DataTables & Bootstrap -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    const table = $('#companyInfoTable').DataTable({
        ajax: "{{ route('superadmin.companyinfo.data') }}",
        processing: true,
        serverSide: true,
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'label' },
            { data: 'value' },
            { data: 'type' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'admin_status', orderable: false, searchable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (row) {
                    return `
                        <button class="btn btn-sm btn-primary btn-edit"
                            data-id="${row.id}"
                            data-title="${row.title}"
                            data-label="${row.label}"
                            data-value="${row.value}"
                            data-type="${row.type}"
                            data-status="${row.status_raw}"
                            data-admin_status="${row.admin_status_raw}">
                            Edit
                        </button>
                    `;
                }
            }
        ]
    });

    // Edit Button Click
    $(document).on('click', '.btn-edit', function () {
        const data = $(this).data();

        $('#edit-id').val(data.id);
        $('#edit-title').val(data.title);
        $('#edit-label').val(data.label);
        $('#edit-type').val(data.type);

        // Set status and admin_status dropdowns
        $('#edit-status').val(data.status ?? '0');
        $('#edit-admin-status').val(data.admin_status ?? '0');

        // Generate value input based on type
        let inputHtml = '';
        switch (data.type) {
            case 'text':
                inputHtml = `<input type="text" class="form-control" name="value" value="${data.value}" required>`;
                break;

            case 'file':
                inputHtml = `
                    <input type="file" class="form-control" name="value">
                    ${data.value ? `<br><a href="/uploads/${data.value}" target="_blank">View Current File</a>` : ''}
                `;
                break;

            case 'select':
            case 'radio':
            case 'checkbox':
                const options = data.value ? data.value.split(',') : [];
                options.forEach(opt => {
                    inputHtml += `
                        <div class="form-check">
                            <input type="${data.type}" name="value[]" value="${opt.trim()}" class="form-check-input">
                            <label class="form-check-label">${opt.trim()}</label>
                        </div>
                    `;
                });
                break;

            default:
                inputHtml = `<input type="text" class="form-control" name="value" value="${data.value}" required>`;
        }

        $('#edit-value-wrapper').html(inputHtml);

        const modal = new bootstrap.Offcanvas('#editModal');
        modal.show();
    });
});
</script>


@endsection
