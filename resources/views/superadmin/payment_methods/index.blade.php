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

    .image-preview {
        max-height: 40px;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">Payment Methods</h5>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#paymentMethodCreateModal">
                    Add Payment Method
                </button>
            </div>
            <div class="card-datatable table-responsive p-2">
                <table id="paymentMethodsTable" class="table datatables-paymentmethods">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Parent Method</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Address</th>
                            <th>Image</th>
                            <th>Is Parent</th>
                            <th>Status</th>
                            <th>Added On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade" id="paymentMethodCreateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('superadmin.paymentmethods.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input name="name" id="name" type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input name="slug" id="slug" type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input name="type" id="type" type="text" class="form-control" >
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input name="address" id="address" type="text" class="form-control">
                    </div>

                   
                    <div class="mb-3">
                        <label for="parent_method" class="form-label">Parent Method</label>
                        <select name="parent_method" id="parent_method" class="form-select">
                            <option value="">-- None --</option>
                            @foreach(\App\Models\PaymentMethod::where('is_parent', 1)->get() as $method)
                                <option value="{{ $method->slug }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="is_parent" class="form-label">Is Parent</label>
                        <select name="is_parent" id="is_parent" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image (optional)</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="paymentMethodEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="" id="paymentMethodEditForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input name="name" id="edit_name" type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_slug" class="form-label">Slug</label>
                        <input name="slug" id="edit_slug" type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <input name="type" id="edit_type" type="text" class="form-control" >
                    </div>

                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <input name="address" id="edit_address" type="text" class="form-control">
                    </div>

            

                    <div class="mb-3">
                        <label for="edit_parent_method" class="form-label">Parent Method</label>
                        <select name="parent_method" id="edit_parent_method" class="form-select">
                            <option value="">-- None --</option>
                            @foreach(\App\Models\PaymentMethod::where('is_parent', 1)->get() as $method)
                                <option value="{{ $method->slug }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_is_parent" class="form-label">Is Parent</label>
                        <select name="is_parent" id="edit_is_parent" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image (optional)</label>
                        <input type="file" class="form-control" name="image">
                        <img src="" id="current_image" class="image-preview mt-2" alt="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(function () {
    $('#paymentMethodsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.paymentmethods.list') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'parent_method', name: 'parent_method' },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'type', name: 'type' },
            { data: 'address', name: 'address' },
            { data: 'image', name: 'image', render: function (data) {
                return data ? `<img src="${data}" class="image-preview" />` : '';
            }},
            { data: 'is_parent', name: 'is_parent' },
            { data: 'status', name: 'status' },
            { data: 'added_on', name: 'added_on' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ]
    });

    $(document).on('click', '.edit-payment-method', function () {
        const id = $(this).data('id');
        $.get(`/superadmin/paymentmethods/${id}/edit`, function (response) {
            $('#paymentMethodEditForm').attr('action', `/superadmin/paymentmethods/${response.id}`);
            $('#edit_name').val(response.name);
            $('#edit_slug').val(response.slug);
            $('#edit_type').val(response.type);
            $('#edit_address').val(response.address);
            $('#edit_parent_method').val(response.parent_method);
            $('#edit_is_parent').val(response.is_parent);
            $('#edit_status').val(response.status);
            $('#edit_added_on').val(response.added_on);
            $('#current_image').attr('src', response.image_url);
            $('#paymentMethodEditModal').modal('show');
        });
    });

    $(document).on('click', '.delete-payment-method', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the payment method.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/superadmin/paymentmethods/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => {
                        Swal.fire('Deleted!', 'Payment method has been deleted.', 'success');
                        $('#paymentMethodsTable').DataTable().ajax.reload();
                    },
                    error: () => {
                        Swal.fire('Error!', 'Failed to delete.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
