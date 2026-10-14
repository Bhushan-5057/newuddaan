@extends('admin.layouts.app')

@section('title', 'Product Management')

@section('content')

<style>
    .upload-box {
        border: 2px dashed #cfd4ff;
        border-radius: 10px;
        padding: 30px;
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

    .upload-text strong {
        display: block;
        font-size: 15px;
        color: #333;
    }

    .upload-text small {
        display: block;
        color: #666;
        font-size: 13px;
        margin-top: 4px;
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

    .dimension-box {
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
</style>

<div class="container py-4">
    <h4>Add Product</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data" class="card p-3 mb-4">
        @csrf

        <div class="row g-2">
            {{-- CATEGORY --}}
            <div class="col-md-4">
                <label>Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- SUB CATEGORY --}}
            <div class="col-md-4" id="subCategoryBox" style="display:none;">
                <label>Sub Category (Optional)</label>
                <select name="sub_category_id" id="subCategorySelect" class="form-control">
                    <option value="">None</option>
                </select>
                @error('sub_category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- PRODUCT CODE --}}
            <div class="col-md-4">
                <label>Product Code <span class="text-danger">*</span></label>
                <input type="text" name="p_code" value="{{ old('p_code') }}"
                    class="form-control @error('p_code') is-invalid @enderror">
                @error('p_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- PRODUCT NAME --}}
            <div class="col-md-4">
                <label>Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- MRP --}}
            <div class="col-md-4">
                <label>MRP <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="mrp" value="{{ old('mrp') }}"
                    class="form-control @error('mrp') is-invalid @enderror">
                @error('mrp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- DP --}}
            <div class="col-md-4">
                <label>DP <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="dp" value="{{ old('dp') }}"
                    class="form-control @error('dp') is-invalid @enderror">
                @error('dp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- GST --}}
            <div class="col-md-4">
                <label>GST % <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="gst_percent" value="{{ old('gst_percent') }}"
                    class="form-control @error('gst_percent') is-invalid @enderror">
                @error('gst_percent')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- HSN --}}
            <div class="col-md-4">
                <label>HSN Code</label>
                <input type="text" name="hsn_code" value="{{ old('hsn_code') }}"
                    class="form-control @error('hsn_code') is-invalid @enderror">
                @error('hsn_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- QTY --}}
            <div class="col-md-4">
                <label>Qty <span class="text-danger">*</span></label>
                <input type="number" name="qty" value="{{ old('qty', 1) }}"
                    class="form-control @error('qty') is-invalid @enderror">
                @error('qty')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- BV --}}
            <div class="col-md-4">
                <label>BV <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="product_bv" value="{{ old('product_bv') }}"
                    class="form-control @error('product_bv') is-invalid @enderror">
                @error('product_bv')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- PV 
            <div class="col-md-4">
                <label>PV <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="pv" value="{{ old('pv', 1) }}"
                    class="form-control @error('pv') is-invalid @enderror">
                @error('pv')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>  --}}
            
            
            <input type="number" step="0.01" name="pv" value="1"
                    class="form-control"  hidden>

            {{-- WEIGHT AND DIMENSIONS SECTION --}}
            <div class="col-12">
                <h5 class="mt-3 mb-2">Weight & Dimensions</h5>
                <div class="dimension-box">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" value="{{ old('weight') }}"
                                class="form-control @error('weight') is-invalid @enderror"
                                placeholder="e.g., 1.5">
                            @error('weight')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Length (cm)</label>
                            <input type="number" step="0.01" name="length" value="{{ old('length') }}"
                                class="form-control @error('length') is-invalid @enderror"
                                placeholder="e.g., 30">
                            @error('length')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Breadth/Width (cm)</label>
                            <input type="number" step="0.01" name="breadth" value="{{ old('breadth') }}"
                                class="form-control @error('breadth') is-invalid @enderror"
                                placeholder="e.g., 20">
                            @error('breadth')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Height (cm)</label>
                            <input type="number" step="0.01" name="height" value="{{ old('height') }}"
                                class="form-control @error('height') is-invalid @enderror"
                                placeholder="e.g., 10">
                            @error('height')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- DESCRIPTIONS --}}
            <div class="col-md-6">
                <label>Short Description</label>
                <textarea name="p_dese" class="form-control @error('p_dese') is-invalid @enderror" rows="5">{{ old('p_dese') }}</textarea>
                @error('p_dese')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label>Full Description</label>
                <textarea name="other_dese" class="form-control @error('other_dese') is-invalid @enderror" rows="5">{{ old('other_dese') }}</textarea>
                @error('other_dese')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- MAIN IMAGE --}}
            <div class="col-md-4">
                <div class="upload-box mt-3">
                    <input type="file" name="imgs" id="mainImage" accept=".jpg,.jpeg,.png,.webp" hidden>
                    <label for="mainImage" class="upload-label">
                        <div class="upload-icon">☁️</div>
                        <strong>Click or drag main image</strong>
                        <small>JPG, PNG, WEBP • Max 2MB • Only 1 image</small>
                    </label>
                    <div id="mainPreview" class="preview-box"></div>
                </div>
                @error('imgs')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- GALLERY --}}
            <div class="col-md-8">
                <div class="upload-box mt-3">
                    <input type="file" name="more_imgs[]" id="moreImages" accept=".jpg,.jpeg,.png,.webp"
                        multiple hidden>
                    <label for="moreImages" class="upload-label">
                        <div class="upload-icon">🖼️</div>
                        <strong>Click or drag gallery images</strong>
                        <small>JPG, PNG, WEBP • Max 4 images • 1MB each</small>
                    </label>
                    <div id="galleryPreview" class="preview-grid"></div>
                </div>
                @error('more_imgs')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
                @error('more_imgs.*')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Add Product</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

<script>
    // Main Image Preview
    document.getElementById('mainImage').addEventListener('change', function() {
        const preview = document.getElementById('mainPreview');
        preview.innerHTML = '';

        if (this.files.length > 1) {
            alert('Only one main image allowed');
            this.value = '';
            return;
        }

        const file = this.files[0];
        if (file) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);
        }
    });

    // Gallery Images Preview
    document.getElementById('moreImages').addEventListener('change', function() {
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = '';

        if (this.files.length > 4) {
            alert('Maximum 4 images allowed');
            this.value = '';
            return;
        }

        Array.from(this.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);
        });
    });

    // Sub Category Fetch
    document.querySelector('[name="category_id"]').addEventListener('change', function() {
        let categoryId = this.value;
        let subBox = document.getElementById('subCategoryBox');
        let subSelect = document.getElementById('subCategorySelect');

        subSelect.innerHTML = '<option value="">None</option>';

        if (!categoryId) {
            subBox.style.display = 'none';
            return;
        }

        fetch(`/admin/get-subcategories/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    subBox.style.display = 'block';
                    data.forEach(sub => {
                        let opt = document.createElement('option');
                        opt.value = sub.id;
                        opt.textContent = sub.name;
                        if ("{{ old('sub_category_id') }}" == sub.id) {
                            opt.selected = true;
                        }
                        subSelect.appendChild(opt);
                    });
                } else {
                    subBox.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching subcategories:', error);
            });
    });

    // Trigger category change on page load if there's old input
    if (document.querySelector('[name="category_id"]').value) {
        document.querySelector('[name="category_id"]').dispatchEvent(new Event('change'));
    }

    // Reset form handling
    document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('mainPreview').innerHTML = '';
        document.getElementById('galleryPreview').innerHTML = '';
        document.querySelector('form').reset();
    });
</script>

@endsection