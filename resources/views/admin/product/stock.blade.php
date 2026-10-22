@extends('admin.layouts.app')
@section('title', 'Stock Management')

@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between mb-3">
            <h4>📦 Stock Management</h4>
        </div>

        {{-- FILTER --}}
        <form class="card p-3 mb-3">
            <div class="row g-2 align-items-end">

                <div class="col-md-3">
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

                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                        placeholder="Product / Code">
                </div>

                <div class="col-md-3">
                    <label>Stock Status</label>
                    <select name="stock" class="form-control">
                        <option value="">All</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>
                            Low Stock
                        </option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>
                            Out of Stock
                        </option>
                    </select>
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
                            <th>Action</th>

                            <th>Image</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Alert</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($products as $p)
                            @php
                                $rowClass = '';
                                if ($p->qty == 0) {
                                    $rowClass = 'table-danger';
                                } elseif ($p->qty <= $p->low_stock_alert) {
                                    $rowClass = 'table-warning';
                                }
                            @endphp

                            <tr class="{{ $rowClass }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#viewStockProductModal" data-name="{{ $p->name }}"
                                        data-code="{{ $p->p_code }}" data-category="{{ $p->category->name ?? '-' }}"
                                        data-qty="{{ $p->qty }}" data-alert="{{ $p->low_stock_alert }}"
                                        data-mrp="{{ $p->mrp }}" data-dp="{{ $p->dp }}"
                                        data-gst="{{ $p->gst_percent }}" data-hsn="{{ $p->hsn_code }}"
                                        data-bv="{{ $p->product_bv }}" data-pv="{{ $p->pv }}"
                                        data-short="{{ $p->p_dese }}" data-full="{{ $p->other_dese }}"
                                        data-img="{{ asset('storage/' . $p->imgs) }}"
                                        data-gallery='@json($p->more_imgs)'>
                                        View
                                    </button>
                                </td>


                                <td>
                                    <img src="{{ asset('storage/' . $p->imgs) }}" width="45" class="rounded">
                                </td>

                                <td>{{ $p->name }}</td>
                                <td>{{ $p->p_code }}</td>
                                <td>{{ $p->category->name ?? '-' }}</td>

                                <td><b>{{ $p->qty }}</b></td>
                                <td>{{ $p->low_stock_alert }}</td>

                                <td>
                                    @if ($p->qty == 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($p->qty <= $p->low_stock_alert)
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No products found
                                </td>
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

    {{-- ================= VIEW STOCK PRODUCT MODAL ================= --}}
    <div class="modal fade" id="viewStockProductModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Product Details</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- BASIC INFO --}}
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Basic Info</div>
                        <div class="card-body row g-2">
                            <div class="col-md-4"><b>Name:</b> <span id="sv_name"></span></div>
                            <div class="col-md-4"><b>Code:</b> <span id="sv_code"></span></div>
                            <div class="col-md-4"><b>Category:</b> <span id="sv_category"></span></div>

                            <div class="col-md-4"><b>Qty:</b> <span id="sv_qty"></span></div>
                            <div class="col-md-4"><b>Low Stock Alert:</b> <span id="sv_alert"></span></div>
                            <div class="col-md-4"><b>HSN:</b> <span id="sv_hsn"></span></div>
                        </div>
                    </div>

                    {{-- PRICE --}}
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Price & Values</div>
                        <div class="card-body row g-2">
                            <div class="col-md-3"><b>MRP:</b> ₹<span id="sv_mrp"></span></div>
                            <div class="col-md-3"><b>DP:</b> ₹<span id="sv_dp"></span></div>
                            <div class="col-md-3"><b>GST %:</b> <span id="sv_gst"></span>%</div>
                            <div class="col-md-3"><b>BV:</b> <span id="sv_bv"></span></div>
                            <div class="col-md-3"><b>PV:</b> <span id="sv_pv"></span></div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Description</div>
                        <div class="card-body">
                            <p><b>Short:</b></p>
                            <p id="sv_short"></p>
                            <p><b>Full:</b></p>
                            <p id="sv_full"></p>
                        </div>
                    </div>

                    {{-- IMAGES --}}
                    <div class="card">
                        <div class="card-header fw-bold">Images</div>
                        <div class="card-body row">
                            <div class="col-md-4 text-center">
                                <b>Main Image</b><br>
                                <img id="sv_img" class="img-fluid rounded border mt-2" style="max-height:220px">
                            </div>

                            <div class="col-md-8">
                                <b>Gallery</b>
                                <div id="sv_gallery" class="preview-grid mt-2"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('viewStockProductModal')
            ?.addEventListener('show.bs.modal', function(e) {

                let b = e.relatedTarget;

                document.getElementById('sv_name').innerText = b.dataset.name;
                document.getElementById('sv_code').innerText = b.dataset.code;
                document.getElementById('sv_category').innerText = b.dataset.category;
                document.getElementById('sv_qty').innerText = b.dataset.qty;
                document.getElementById('sv_alert').innerText = b.dataset.alert;
                document.getElementById('sv_hsn').innerText = b.dataset.hsn;

                document.getElementById('sv_mrp').innerText = b.dataset.mrp;
                document.getElementById('sv_dp').innerText = b.dataset.dp;
                document.getElementById('sv_gst').innerText = b.dataset.gst;
                document.getElementById('sv_bv').innerText = b.dataset.bv;
                document.getElementById('sv_pv').innerText = b.dataset.pv;

                document.getElementById('sv_short').innerText = b.dataset.short;
                document.getElementById('sv_full').innerText = b.dataset.full;

                document.getElementById('sv_img').src = b.dataset.img;

                let gallery = JSON.parse(b.dataset.gallery || '[]');
                let html = '';
                const storageBase = @json(rtrim(asset('storage'), '/'));
                gallery.forEach(img => {
                    html += `<img src="${storageBase}/${String(img).replace(/^\/+/, '')}"
                 style="width:100px;margin:5px;border-radius:6px;border:1px solid #ddd">`;
                });
                document.getElementById('sv_gallery').innerHTML = html;
            });
    </script>

@endsection
