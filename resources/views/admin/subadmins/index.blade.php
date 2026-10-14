@extends('admin.layouts.app')

@section('content')

<style>

    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }
    @media (max-width: 768px) {
    .modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }
}
</style>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Sub Admin Management</h4>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-user-plus me-1"></i>
                Add Sub Admin
            </button>
        </div>

        <!-- Sub Admin List -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subadmins as $s)
                            <tr>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary editBtn" data-id="{{ $s->id }}">
                                        <i class="bx bx-edit"></i>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No Sub Admin Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ================= ADD MODAL ================= --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        {{-- <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <form method="POST" action="{{ route('admin.subadmin.store') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Sub Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input name="username" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-semibold mb-2">Permissions</h6>

            <ul class="list-group">
@foreach($permissions as $p)
    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
        <span class="fw-semibold">{{ $p }}</span>

        <label class="form-check mt-1">
            <input class="form-check-input"
                   type="checkbox"
                   name="permissions[{{ $p }}][view]">
            View
        </label>
    </li>
@endforeach
</ul>


                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ================= EDIT MODAL ================= --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form method="POST" action="{{ route('admin.subadmin.update') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Sub Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-semibold mb-2">Permissions</h6>

                        <div id="editPermissions"></div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

{{-- ================= PAGE SCRIPT ================= --}}
@section('scripts')
    <script>
        $(document).ready(function() {

            $('.editBtn').on('click', function() {

                let id = $(this).data('id');

                $.get('/admin/sub-admin/' + id, function(res) {

                    $('#edit_id').val(res.user.id);
                    $('#edit_name').val(res.user.name);
                    $('#edit_email').val(res.user.email);

                    let html = `<ul class="list-group">`;

                    @foreach ($permissions as $p)
                        let perm{{ Str::slug($p, '_') }} =
                            res.permissions.find(x => x.permission_slug ===
                            '{{ $p }}') || {};

                        html += `
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fw-semibold">{{ $p }}</span>

            <label class="form-check mb-0">
                <input class="form-check-input"
                       type="checkbox"
                       name="permissions[{{ $p }}][view]"
                       ${perm{{ Str::slug($p, '_') }}.can_view == 1 ? 'checked' : ''}>
                <span class="form-check-label">View</span>
            </label>
        </li>
    `;
                    @endforeach

                    html += `</ul>`;

                    $('#editPermissions').html(html);


                    $('#editPermissions').html(html);

                    let modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                });
            });

        });
    </script>
@endsection
