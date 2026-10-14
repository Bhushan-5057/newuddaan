@extends('admin.layouts.app')
@section('title', 'Sub Category Management')

@section('content')
    <div class="container py-4">

        <h4>Sub Category Management</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e)
                    {{ $e }} <br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.subcategory.store') }}" class="card p-3 mb-4">
            @csrf

            <select name="category_id" class="form-control mb-2" required>
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <input type="text" name="name" class="form-control mb-2" placeholder="Sub Category Name" required>

            <button class="btn btn-success">Add Sub Category</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sub Category</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subCategories as $sub)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sub->name }}</td>
                        <td>{{ $sub->category->name }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSubModal"
                                data-id="{{ $sub->id }}" data-name="{{ $sub->name }}"
                                data-cat="{{ $sub->category_id }}">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSubModal"
                                data-id="{{ $sub->id }}" data-name="{{ $sub->name }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editSubModal">
        <div class="modal-dialog">
            <form method="POST" id="editSubForm">
                @csrf @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Sub Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <select name="category_id" id="edit_sub_cat" class="form-control mb-2" required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <input type="text" name="name" id="edit_sub_name" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="modal fade" id="deleteSubModal">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="deleteSubForm">
                @csrf @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5>Delete Sub Category</h5>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete
                        <strong id="deleteSubName"></strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger">Yes Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const subDeleteRoute = "{{ route('admin.subcategory.delete', ':id') }}";
        const subUpdateRoute = "{{ route('admin.subcategory.update', ':id') }}";

        document.getElementById('deleteSubModal')
            .addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget;

                let id = button.getAttribute('data-id');
                let name = button.getAttribute('data-name');

                document.getElementById('deleteSubName').innerText = name;
                document.getElementById('deleteSubForm')
                    .setAttribute('action', subDeleteRoute.replace(':id', id));
            });

        document.getElementById('editSubModal')
            .addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget;

                let id = button.getAttribute('data-id');
                let name = button.getAttribute('data-name');
                let cat = button.getAttribute('data-cat');

                document.getElementById('edit_sub_name').value = name;
                document.getElementById('edit_sub_cat').value = cat;

                document.getElementById('editSubForm')
                    .setAttribute('action', subUpdateRoute.replace(':id', id));
            });
    </script>

@endsection
