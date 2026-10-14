@extends('superadmin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h5 class="card-title mb-0">Permissions</h5>
            <div class="d-flex justify-content-end my-2">
                <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#createPermissionModal">
                    Create Permission
                </button>
            </div>

            <select id="roleSelector" class="form-select w-auto ms-3">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="card-datatable table-responsive p-2">
            <table id="permissionsTable" class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Feature Name</th>
                        <th>View</th>
                        <th>Create</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- create modal  -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="createPermissionModal"
    aria-labelledby="offcanvascreatePermissionModalLabel">
    <div class="offcanvas-header border-bottom">
        <h6 id="offcanvascreatePermissionModalLabel" class="offcanvas-title">Create Permission</h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf

            {{-- Feature Name --}}
            <div class="mb-3">
                <label for="featureName" class="form-label">Feature Name</label>
                <input type="text" class="form-control" id="featureName" name="feature" required>
            </div>

            {{-- Role Select --}}
            <div class="mb-3">
                <label for="roleSelect" class="form-label">Role</label>
                <select class="form-select" id="roleSelect" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            {{-- Permissions --}}
            <label class="form-label d-block">Permissions</label>
            <div class="form-check form-check-inline" id="perm-view">
                <input class="form-check-input" type="checkbox" name="can_view" id="createCanView">
                <label class="form-check-label" for="createCanView">View</label>
            </div>
            <div class="form-check form-check-inline" id="perm-create">
                <input class="form-check-input" type="checkbox" name="can_create" id="createCanCreate">
                <label class="form-check-label" for="createCanCreate">Create</label>
            </div>
            <div class="form-check form-check-inline" id="perm-edit">
                <input class="form-check-input" type="checkbox" name="can_edit" id="createCanEdit">
                <label class="form-check-label" for="createCanEdit">Edit</label>
            </div>
            <div class="form-check form-check-inline" id="perm-delete">
                <input class="form-check-input" type="checkbox" name="can_delete" id="createCanDelete">
                <label class="form-check-label" for="createCanDelete">Delete</label>
            </div>

            {{-- Submit --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Edit Modal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editPermissionModal"
    aria-labelledby="offcanvasEditPermissionModalLabel">
    <div class="offcanvas-header border-bottom">
        <h6 id="offcanvasEditPermissionModalLabel" class="offcanvas-title">Edit Permission</h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" id="editPermissionForm">
            @csrf
            @method('PUT')

            <!-- Hidden ID field -->
            <input type="hidden" name="id" id="editPermissionId">

            <!-- Feature Name -->
            <div class="mb-3">
                <label for="editFeatureName" class="form-label">Feature Name</label>
                <input type="text" class="form-control" id="editFeatureName" name="feature" required>
            </div>

            <!-- Role Select -->
            <div class="mb-3">
                <label for="editRoleSelect" class="form-label">Role</label>
                <select class="form-select" id="editRoleSelect" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Permissions -->
            <label class="form-label d-block">Permissions</label>
            <div class="form-check form-check-inline" id="perm-view-edit">
                <input class="form-check-input" type="checkbox" name="can_view" id="editCanView">
                <label class="form-check-label" for="editCanView">View</label>
            </div>
            <div class="form-check form-check-inline" id="perm-create-edit">
                <input class="form-check-input" type="checkbox" name="can_create" id="editCanCreate">
                <label class="form-check-label" for="editCanCreate">Create</label>
            </div>
            <div class="form-check form-check-inline" id="perm-edit-edit">
                <input class="form-check-input" type="checkbox" name="can_edit" id="editCanEdit">
                <label class="form-check-label" for="editCanEdit">Edit</label>
            </div>
            <div class="form-check form-check-inline" id="perm-delete-edit">
                <input class="form-check-input" type="checkbox" name="can_delete" id="editCanDelete">
                <label class="form-check-label" for="editCanDelete">Delete</label>
            </div>

            <!-- Submit -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let table;
function loadTable(role = 'admin') {
    if (table) {
        table.destroy();
        $('#permissionsTable tbody').empty();
    }

    let columns = [
        {
            data: 'feature',
            name: 'feature'
        },
        {
            data: 'can_view',
            render: function(data, type, row) {
                return data
                    ? `<input type="checkbox" class="perm-checkbox" data-role="${role}" data-feature="${row.feature}" data-perm="can_view" ${row.can_view_checkbox == 1 ? 'checked' : ''} >`
                    : '';
            }
        },
        {
            data: 'can_create',
            render: function(data, type, row) {
                return data
                    ? `<input type="checkbox" class="perm-checkbox" data-role="${role}" data-feature="${row.feature}" data-perm="can_create" ${row.can_create_checkbox == 1 ? 'checked' : ''}>`
                    : '';
            }
        },
        {
            data: 'can_edit',
            render: function(data, type, row) {
                return data
                    ? `<input type="checkbox" class="perm-checkbox" data-role="${role}" data-feature="${row.feature}" data-perm="can_edit" ${row.can_edit_checkbox == 1 ? 'checked' : ''}>`
                    : '';
            }
        },
        {
            data: 'can_delete',
            render: function(data, type, row) {
                return data
                    ? `<input type="checkbox" class="perm-checkbox" data-role="${role}" data-feature="${row.feature}" data-perm="can_delete" ${row.can_delete_checkbox == 1 ? 'checked' : ''}>`
                    : '';
            }
        },
         {
        data: null,
        orderable: false,
        searchable: false,
        render: function(data, type, row) {
            // Use data-permission for passing full row JSON
            return `<button class="btn btn-sm btn-warning edit-permission-btn"
                data-permission='${JSON.stringify(row)}'>Edit</button>
                 <button class="btn btn-sm btn-danger delete-permission-btn"
                data-id="${row.id}">Delete</button>`;
        }
    }

    ];

    table = $('#permissionsTable').DataTable({
        ajax: '{{ route("permissions.data") }}' + '?role=' + role,
        columns: columns
    });

    adjustTableHeaders(role);
}



function adjustTableHeaders(role) {
    let headerRow = $('#permissionsTable thead tr');
    headerRow.empty(); 
    headerRow.append('<th>Feature Name</th><th>View</th><th>Create</th><th>Edit</th><th>Delete</th><th>Action</th>');

    
}

$(document).ready(function () {
    let selectedRole = $('#roleSelector').val();
    loadTable(selectedRole);

    $('#roleSelector').on('change', function () {
        selectedRole = $(this).val();
        loadTable(selectedRole);
    });

    // Handle checkbox toggle
    $('#permissionsTable').on('change', '.perm-checkbox', function () {
        const role = $(this).data('role');
        const feature = $(this).data('feature');
        const permission = $(this).data('perm');
        const value = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("permissions.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                role: role,
                feature: feature,
                permission: permission,
                value: value
            },
            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Permission Updated',
                    text: res.message || 'Updated successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: xhr.responseJSON?.message || 'Something went wrong.'
                });
            }
        });
    });
});


// Show/hide checkboxes on form
// function togglePermissionCheckboxes(role) {
//     if (role === 'user') {
//         $('#perm-create, #perm-edit, #perm-delete').hide();
//         $('#perm-view').show();
//         $('#createCanCreate, #createCanEdit, #createCanDelete').prop('checked', false);
//     } else {
//         $('#perm-create, #perm-edit, #perm-delete, #perm-view').show();
//     }
// }

$('#roleSelect').on('change', function() {
    togglePermissionCheckboxes($(this).val());
});

$('#createPermissionModal').on('shown.bs.offcanvas', function () {
    togglePermissionCheckboxes($('#roleSelect').val());
});


$(document).on('click', '.edit-permission-btn', function () {
    const permission = $(this).data('permission'); // permission is expected as object

    $('#editPermissionId').val(permission.id);
    $('#editFeatureName').val(permission.feature);
    $('#editRoleSelect').val(permission.role);

    $('#editCanView').prop('checked', permission.can_view == 1);
    $('#editCanCreate').prop('checked', permission.can_create == 1);
    $('#editCanEdit').prop('checked', permission.can_edit == 1);
    $('#editCanDelete').prop('checked', permission.can_delete == 1);

    // Set the form action
 if (permission.id) {
    $('#editPermissionForm').attr('action', `/permissions/${permission.id}`);
} else {
    alert('Invalid permission ID.');
}

    // Show the modal
    let editModal = new bootstrap.Offcanvas(document.getElementById('editPermissionModal'));
    editModal.show();
});



$(document).on('click', '.delete-permission-btn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/permissions/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete permission.', 'error');
                }
            });
        }
    });
});




</script>
@endsection


