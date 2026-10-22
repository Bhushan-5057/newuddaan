@extends('admin.layouts.app')

@section('title', 'Product List')

@section('content')

<style>
    .upload-box {
        border: 2px dashed #cfd4ff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        /* background: #fafbff; */
    }

    .upload-box:hover {
        border-color: #6c63ff;
        /* background: #f5f6ff; */
    }

    .upload-label {
        display: block;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 36px;
        margin-bottom: 10px;
    }

    .preview-box img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
        border: 1px solid #ddd;
    }

    .preview-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .preview-grid img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
</style>

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Product List</h4>
        <a href="{{ route('admin.product.index') }}" class="btn btn-success">
            + Add Product
        </a>
    </div>

    {{-- FILTER --}}
    <form class="card p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option value="">All</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Search</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                    placeholder="Product name / code">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>View</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>BV</th>
                        <th>MRP</th>
                        <th>DP</th>
                        <th>Status</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewProductModal" data-name="{{ $p->name }}"
                                    data-code="{{ $p->p_code }}" data-category="{{ $p->category->name ?? '-' }}"
                                    data-subcategory="{{ $p->subCategory->name ?? '' }}" data-mrp="{{ $p->mrp }}"
                                    data-dp="{{ $p->dp }}" data-gst="{{ $p->gst_percent }}"
                                    data-hsn="{{ $p->hsn_code }}" data-qty="{{ $p->qty }}"
                                    data-bv="{{ $p->product_bv }}"
                                    data-pv="{{ $p->pv }}"
                                    data-weight="{{ $p->weight }}"
                                    data-length="{{ $p->length }}"
                                    data-breadth="{{ $p->breadth }}"
                                    data-height="{{ $p->height }}"
                                    data-short="{{ $p->p_dese }}" data-full="{{ $p->other_dese }}"
                                    data-img="{{ asset('storage/' . $p->imgs) }}"
                                    data-gallery='@json($p->more_imgs)'>
                                    View
                                </button>
                            </td>
                            <td><img src="{{ asset('storage/' . $p->imgs) }}" width="50"></td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->p_code }}</td>
                            <td>{{ $p->category->name ?? '-' }}</td>
                            <td>{{ $p->product_bv }}</td>
                            <td>{{ $p->mrp }}</td>
                            <td>{{ $p->dp }}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProductModal"
                                    data-id="{{ $p->id }}"
                                    data-name="{{ $p->name }}"
                                    data-mrp="{{ $p->mrp }}"
                                    data-dp="{{ $p->dp }}"
                                    data-gst="{{ $p->gst_percent }}"
                                    data-hsn="{{ $p->hsn_code }}"
                                    data-qty="{{ $p->qty }}"
                                    data-bv="{{ $p->product_bv }}"
                                    data-pv="{{ $p->pv }}"
                                    data-weight="{{ $p->weight }}"
                                    data-length="{{ $p->length }}"
                                    data-breadth="{{ $p->breadth }}"
                                    data-height="{{ $p->height }}"
                                    data-short="{{ $p->p_dese }}"
                                    data-full="{{ $p->other_dese }}"
                                    data-category="{{ $p->category_id }}"
                                    data-subcategory="{{ $p->sub_category_id }}"
                                    data-img="{{ asset('storage/' . $p->imgs) }}"
                                    data-gallery='@json($p->more_imgs)'>
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $p->id }}"
                                    data-name="{{ $p->name }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="card-footer">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- ================= VIEW MODAL ================= --}}
<div class="modal fade" id="viewProductModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- BASIC INFO --}}
                <div class="card mb-3">
                    <div class="card-header fw-bold">Basic Information</div>
                    <div class="card-body row g-2">
                        <div class="col-md-4"><b>Name:</b> <span id="v_name"></span></div>
                        <div class="col-md-4"><b>Code:</b> <span id="v_code"></span></div>
                        <div class="col-md-4"><b>Category:</b> <span id="v_category"></span></div>
                        <div class="col-md-4 d-none" id="subCategoryBox">
                            <b>Sub Category:</b> <span id="v_subcategory"></span>
                        </div>
                        <div class="col-md-4"><b>Qty:</b> <span id="v_qty"></span></div>
                        <div class="col-md-4"><b>HSN:</b> <span id="v_hsn"></span></div>
                    </div>
                </div>

                {{-- PRICE INFO --}}
                <div class="card mb-3">
                    <div class="card-header fw-bold">Price & Business Values</div>
                    <div class="card-body row g-2">
                        <div class="col-md-3"><b>MRP:</b> ₹<span id="v_mrp"></span></div>
                        <div class="col-md-3"><b>DP:</b> ₹<span id="v_dp"></span></div>
                        <div class="col-md-3"><b>GST %:</b> <span id="v_gst"></span>%</div>
                        <div class="col-md-3"><b>BV:</b> <span id="v_bv"></span></div>
                    </div>
                </div>

                {{-- DIMENSIONS INFO --}}
                <div class="card mb-3">
                    <div class="card-header fw-bold">Weight & Dimensions</div>
                    <div class="card-body row g-2">
                        <div class="col-md-3"><b>Weight:</b> <span id="v_weight"></span> kg</div>
                        <div class="col-md-3"><b>Length:</b> <span id="v_length"></span> cm</div>
                        <div class="col-md-3"><b>Breadth:</b> <span id="v_breadth"></span> cm</div>
                        <div class="col-md-3"><b>Height:</b> <span id="v_height"></span> cm</div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="card mb-3">
                    <div class="card-header fw-bold">Description</div>
                    <div class="card-body">
                        <p><b>Short:</b></p>
                        <p id="v_short"></p>
                        <p><b>Full:</b></p>
                        <p id="v_full"></p>
                    </div>
                </div>

                {{-- IMAGES --}}
                <div class="card">
                    <div class="card-header fw-bold">Images</div>
                    <div class="card-body row">
                        <div class="col-md-4 text-center">
                            <b>Main Image</b><br>
                            <img id="v_img" class="img-fluid rounded border mt-2" style="max-height:220px">
                        </div>
                        <div class="col-md-8">
                            <b>Gallery Images</b>
                            <div id="v_gallery" class="preview-grid mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= EDIT MODAL ================= --}}
<div class="modal fade" id="editProductModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="category_id" id="e_category_id">
            <input type="hidden" name="sub_category_id" id="e_sub_category_id">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-2">
                    <div class="col-md-6">
                        <label>Product Name</label>
                        <input type="text" name="name" id="e_name" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>MRP</label>
                        <input type="number" step="0.01" name="mrp" id="e_mrp" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>DP</label>
                        <input type="number" step="0.01" name="dp" id="e_dp" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>GST %</label>
                        <input type="number" step="0.01" name="gst_percent" id="e_gst" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>HSN</label>
                        <input type="text" name="hsn_code" id="e_hsn" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Qty</label>
                        <input type="number" name="qty" id="e_qty" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>BV</label>
                        <input type="number" step="0.01" name="product_bv" id="e_bv" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>PV</label>
                        <input type="number" step="0.01" name="pv" id="e_pv" class="form-control">
                    </div>

                    {{-- WEIGHT & DIMENSIONS --}}
                    <div class="col-12">
                        <h6 class="mt-2">Weight & Dimensions</h6>
                    </div>
                    <div class="col-md-3">
                        <label>Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" id="e_weight" class="form-control" placeholder="e.g., 1.5">
                    </div>
                    <div class="col-md-3">
                        <label>Length (cm)</label>
                        <input type="number" step="0.01" name="length" id="e_length" class="form-control" placeholder="e.g., 30">
                    </div>
                    <div class="col-md-3">
                        <label>Breadth (cm)</label>
                        <input type="number" step="0.01" name="breadth" id="e_breadth" class="form-control" placeholder="e.g., 20">
                    </div>
                    <div class="col-md-3">
                        <label>Height (cm)</label>
                        <input type="number" step="0.01" name="height" id="e_height" class="form-control" placeholder="e.g., 10">
                    </div>

                    {{-- DESCRIPTIONS --}}
                    <div class="col-12">
                        <h6 class="mt-2">Descriptions</h6>
                    </div>
                    <div class="col-md-6">
                        <label>Short Description</label>
                        <textarea name="p_dese" id="e_short" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Full Description</label>
                        <textarea name="other_dese" id="e_full" class="form-control" rows="4"></textarea>
                    </div>

                    {{-- IMAGES SECTION --}}
                    <div class="col-12">
                        <h6 class="mt-2">Images</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="upload-box">
                            <input type="file" name="imgs" id="editMainImage" accept=".jpg,.jpeg,.png,.webp" hidden>
                            <label for="editMainImage" class="upload-label">
                                <div class="upload-icon">☁️</div>
                                <strong>Click to change main image</strong>
                                <small>JPG, PNG, WEBP • Optional</small>
                            </label>
                            <div id="editMainPreview" class="preview-box"></div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="upload-box">
                            <input type="file" name="more_imgs[]" id="editGalleryImages" accept=".jpg,.jpeg,.png,.webp" multiple hidden>
                            <label for="editGalleryImages" class="upload-label">
                                <div class="upload-icon">🖼️</div>
                                <strong>Click to change gallery images</strong>
                                <small>Max 4 images • Optional</small>
                            </label>
                            <div id="editGalleryPreview" class="preview-grid"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5>Delete Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to delete <b id="d_name"></b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
    const updateUrl = "{{ route('admin.product.update', ':id') }}";
    const deleteUrl = "{{ url('admin/product/delete') }}/:id";

    /* ================= VIEW ================= */
    document.getElementById('viewProductModal')?.addEventListener('show.bs.modal', function(e) {
        let b = e.relatedTarget;
        
        document.getElementById('v_name').innerText = b.dataset.name;
        document.getElementById('v_code').innerText = b.dataset.code;
        document.getElementById('v_category').innerText = b.dataset.category;
        document.getElementById('v_mrp').innerText = b.dataset.mrp;
        document.getElementById('v_dp').innerText = b.dataset.dp;
        document.getElementById('v_gst').innerText = b.dataset.gst;
        document.getElementById('v_hsn').innerText = b.dataset.hsn;
        document.getElementById('v_qty').innerText = b.dataset.qty;
        document.getElementById('v_bv').innerText = b.dataset.bv;
        document.getElementById('v_short').innerText = b.dataset.short;
        document.getElementById('v_full').innerText = b.dataset.full;
        document.getElementById('v_weight').innerText = b.dataset.weight || '-';
        document.getElementById('v_length').innerText = b.dataset.length || '-';
        document.getElementById('v_breadth').innerText = b.dataset.breadth || '-';
        document.getElementById('v_height').innerText = b.dataset.height || '-';
        document.getElementById('v_img').src = b.dataset.img;
        
        // Show sub category if exists
        if (b.dataset.subcategory) {
            document.getElementById('v_subcategory').innerText = b.dataset.subcategory;
            document.getElementById('subCategoryBox').classList.remove('d-none');
        } else {
            document.getElementById('subCategoryBox').classList.add('d-none');
        }
        
        let gallery = JSON.parse(b.dataset.gallery || '[]');
        let g = '';
        const storageBase = @json(rtrim(asset('storage'), '/'));
        gallery.forEach(i => {
            g += `<img src="${storageBase}/${String(i).replace(/^\/+/, '')}" style="width:100px;margin:5px;border-radius:6px">`;
        });
        document.getElementById('v_gallery').innerHTML = g;
    });

    /* ================= EDIT ================= */
    document.getElementById('editProductModal')?.addEventListener('show.bs.modal', function(e) {
        let b = e.relatedTarget;
        
        document.getElementById('e_name').value = b.dataset.name;
        document.getElementById('e_mrp').value = b.dataset.mrp;
        document.getElementById('e_dp').value = b.dataset.dp;
        document.getElementById('e_gst').value = b.dataset.gst;
        document.getElementById('e_hsn').value = b.dataset.hsn;
        document.getElementById('e_qty').value = b.dataset.qty;
        document.getElementById('e_bv').value = b.dataset.bv;
        document.getElementById('e_pv').value = b.dataset.pv;
        document.getElementById('e_weight').value = b.dataset.weight || '';
        document.getElementById('e_length').value = b.dataset.length || '';
        document.getElementById('e_breadth').value = b.dataset.breadth || '';
        document.getElementById('e_height').value = b.dataset.height || '';
        document.getElementById('e_short').value = b.dataset.short || '';
        document.getElementById('e_full').value = b.dataset.full || '';
        document.getElementById('e_category_id').value = b.dataset.category;
        document.getElementById('e_sub_category_id').value = b.dataset.subcategory || '';
        
        // MAIN IMAGE PREVIEW
        document.getElementById('editMainPreview').innerHTML = `<img src="${b.dataset.img}" style="width:140px;border-radius:8px">`;
        
        // GALLERY PREVIEW
        let gallery = JSON.parse(b.dataset.gallery || '[]');
        let html = '';
        const storageBaseEdit = @json(rtrim(asset('storage'), '/'));
        gallery.forEach(img => {
            html += `<img src="${storageBaseEdit}/${String(img).replace(/^\/+/, '')}" style="width:100px;margin:4px;border-radius:6px">`;
        });
        document.getElementById('editGalleryPreview').innerHTML = html;
        
        document.getElementById('editForm').action = updateUrl.replace(':id', b.dataset.id);
    });

    // Edit Modal Image Preview for new selections
    document.getElementById('editMainImage')?.addEventListener('change', function() {
        const preview = document.getElementById('editMainPreview');
        if (this.files && this.files[0]) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(this.files[0]);
            img.style.width = '140px';
            img.style.borderRadius = '8px';
            preview.innerHTML = '';
            preview.appendChild(img);
        }
    });
    
    document.getElementById('editGalleryImages')?.addEventListener('change', function() {
        const preview = document.getElementById('editGalleryPreview');
        preview.innerHTML = '';
        if (this.files.length > 4) {
            alert('Maximum 4 images allowed');
            this.value = '';
            return;
        }
        Array.from(this.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.width = '100px';
            img.style.margin = '4px';
            img.style.borderRadius = '6px';
            preview.appendChild(img);
        });
    });

    /* ================= DELETE ================= */
    document.getElementById('deleteModal')?.addEventListener('show.bs.modal', function(e) {
        let b = e.relatedTarget;
        document.getElementById('d_name').innerText = b.dataset.name;
        document.getElementById('deleteForm').action = deleteUrl.replace(':id', b.dataset.id);
    });
</script>

@endsection