{{-- resources/views/admin/category/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="container py-4">
    <h4>Category Management</h4>

    @include('main.layouts.alerts')

    <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data" class="card p-3 mb-4 shadow-sm">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-2">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-6 mb-2">
                <label>Category Image</label>
                <input type="file" name="image" class="form-control" onchange="previewImage(this, 'add_img_preview')" accept="image/*">
                <div class="mt-2"><img id="add_img_preview" src="" style="max-height: 80px; display: none;" class="img-thumbnail"></div>
            </div>
            <div class="col-md-6 mb-2">
                <label>Category Icon</label>
                <input type="file" name="icon" class="form-control" onchange="previewImage(this, 'add_icon_preview')" accept="image/*">
                <div class="mt-2"><img id="add_icon_preview" src="" style="max-height: 80px; display: none;" class="img-thumbnail"></div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="bogo_status" id="bogo_status" value="1">
                    <label class="form-check-label" for="bogo_status">Enable Buy 1 Get 1 Offer</label>
                </div>
            </div>
        </div>

        <button class="btn btn-success mt-2">Add Category</button>
    </form>

    <div class="card shadow-sm p-3">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Sub Category</th>
                    <th>Direct Product</th>
                    <th>BOGO Offer</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($cat->image)
                                <img src="{{ asset('storage/'.$cat->image) }}" width="45" height="45" style="object-fit: cover;" class="rounded border">
                            @else
                                <span class="text-muted small">No Img</span>
                            @endif
                        </td>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->has_subcategory ? 'Yes' : 'No' }}</td>
                        <td>{{ $cat->allow_direct_product ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input bogo-toggle" type="checkbox" 
                                    data-id="{{ $cat->id }}"
                                    {{ $cat->bogo_status ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    <span class="badge {{ $cat->bogo_status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $cat->bogo_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal" 
                                data-id="{{ $cat->id }}"
                                data-name="{{ $cat->name }}" 
                                data-has_sub="{{ $cat->has_subcategory }}"
                                data-direct="{{ $cat->allow_direct_product }}"
                                data-bogo="{{ $cat->bogo_status }}"
                                data-image="{{ $cat->image ? asset('storage/'.$cat->image) : '' }}"
                                data-icon="{{ $cat->icon ? asset('storage/'.$cat->icon) : '' }}">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteCategoryModal" data-id="{{ $cat->id }}"
                                data-name="{{ $cat->name }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editCategoryForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header"><h5>Edit Category</h5></div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Category Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="bogo_status" id="edit_bogo_status" value="1">
                            <label class="form-check-label" for="edit_bogo_status">Enable Buy 1 Get 1 Offer</label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Category Image</label>
                        <input type="file" name="image" class="form-control" onchange="previewImage(this, 'edit_img_preview')">
                        <img id="edit_img_preview" src="" class="img-thumbnail mt-2" style="max-height: 80px;">
                    </div>
                    <div class="mb-2">
                        <label>Category Icon</label>
                        <input type="file" name="icon" class="form-control" onchange="previewImage(this, 'edit_icon_preview')">
                        <img id="edit_icon_preview" src="" class="img-thumbnail mt-2" style="max-height: 80px;">
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-success">Update</button></div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteCategoryForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">
                        Are you sure you want to delete  
                        <strong id="deleteCategoryName"></strong> ?
                    </p>
                    <div class="alert alert-warning small mb-0">
                        ⚠️ This will permanently delete:
                        <ul class="mb-0">
                            <li>Category</li>
                            <li>All Sub Categories</li>
                            <li>All Products under this category</li>
                            <li>All related images/icons</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger">Yes, Delete Everything</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Image Preview Function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Routes
    const categoryUpdateRoute = "{{ route('admin.category.update', ':id') }}";
    const categoryDeleteRoute = "{{ route('admin.category.delete', ':id') }}";
    const toggleBogoRoute = "{{ route('admin.category.toggleBogo', ':id') }}";

    // Edit Modal Logic
    var editModal = document.getElementById('editCategoryModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        let btn = event.relatedTarget;
        let id = btn.getAttribute('data-id');

        document.getElementById('edit_name').value = btn.getAttribute('data-name');
        document.getElementById('edit_has_sub').value = btn.getAttribute('data-has_sub');
        document.getElementById('edit_direct').value = btn.getAttribute('data-direct');
        
        // Set BOGO checkbox
        let bogoStatus = btn.getAttribute('data-bogo');
        document.getElementById('edit_bogo_status').checked = bogoStatus == '1';
        
        document.getElementById('edit_img_preview').src = btn.getAttribute('data-image');
        document.getElementById('edit_icon_preview').src = btn.getAttribute('data-icon');

        document.getElementById('editCategoryForm').action = categoryUpdateRoute.replace(':id', id);
    });

    // Delete Modal Logic
    var deleteModal = document.getElementById('deleteCategoryModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        let btn = event.relatedTarget;
        let id = btn.getAttribute('data-id');
        document.getElementById('deleteCategoryName').innerText = btn.getAttribute('data-name');
        document.getElementById('deleteCategoryForm').action = categoryDeleteRoute.replace(':id', id);
    });

    // BOGO Toggle with AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.bogo-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const id = this.dataset.id;
                const url = toggleBogoRoute.replace(':id', id);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update badge
                        const badge = this.closest('td').querySelector('.badge');
                        if (data.bogo_status) {
                            badge.className = 'badge bg-success';
                            badge.textContent = 'Active';
                        } else {
                            badge.className = 'badge bg-secondary';
                            badge.textContent = 'Inactive';
                        }
                        
                        // Show success message
                        // You can add a toast notification here
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert checkbox if error
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>

@endsection