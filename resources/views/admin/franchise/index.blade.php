@extends('admin.layouts.app')
@section('title', 'Franchise List')

@section('content')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <div class="container py-3">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Franchise List</h4>
            <a href="{{ route('admin.franchise.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Franchise
            </a>
        </div>

        {{-- STATS --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card p-3">
                    <strong>Total</strong>
                    <h4>{{ $total }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-success">
                    <strong>Active</strong>
                    <h4>{{ $active }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-muted">
                    <strong>Inactive</strong>
                    <h4>{{ $inactive }}</h4>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="card p-3 mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label>Status</label>
                    <select id="status" class="form-control">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <button id="filter" class="btn btn-primary w-100">
                        Apply Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">
                <table id="franchiseTable" class="table table-bordered table-striped w-100">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>


    <div class="offcanvas offcanvas-end" id="editFranchise">
        <div class="offcanvas-header">
            <h5>Edit Franchise</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <form id="editForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit-id">

                <div class="row g-3">

                    {{-- LEFT --}}
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" id="edit-name" class="form-control">

                        <label class="mt-2">Username</label>
                        <input type="text" id="edit-username" class="form-control" disabled>

                        <label class="mt-2">Email</label>
                        <input type="email" id="edit-email" class="form-control">

                        <label class="mt-2">Mobile</label>
                        <input type="text" id="edit-mobile" class="form-control">

                        <label class="mt-2">Country</label>
                        <input type="text" id="edit-country" class="form-control">

                        <label class="mt-2">State</label>
                        <input type="text" id="edit-state" class="form-control">

                        <label class="mt-2">Pin Code</label>
                        <input type="text" id="edit-pin" class="form-control">

                        <label class="mt-2">Password</label>
                        <input type="password" id="edit-password" class="form-control">
                    </div>

                    {{-- RIGHT --}}
                    <div class="col-md-6">
                        <label>Nominee Relation</label>
                        <input type="text" id="edit-nominee_relation" class="form-control">

                        <label class="mt-2">Nominee Name</label>
                        <input type="text" id="edit-nominee_name" class="form-control">

                        <label class="mt-2">Franchise Name</label>
                        <input type="text" id="edit-franchise_name" class="form-control">

                        <label class="mt-2">PAN</label>
                        <input type="text" id="edit-pan" class="form-control">

                        <label class="mt-2">GST</label>
                        <input type="text" id="edit-gst" class="form-control">

                        <label class="mt-2">City</label>
                        <input type="text" id="edit-city" class="form-control">

                        <label class="mt-2">Address</label>
                        <textarea id="edit-address" class="form-control"></textarea>

                        <label class="mt-2">Confirm Password</label>
                        <input type="password" id="edit-password_confirmation" class="form-control">

                        <label class="mt-2">Status</label>
                        <select id="edit-status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <button class="btn btn-primary mt-4 w-100">UPDATE</button>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- edit form submit --}}
    <script>
     $('#franchiseTable').on('click', '.btn-edit', function () {

    let id = $(this).data('id');
    console.log('Edit clicked ID:', id);

    let editUrl = "{{ route('admin.franchise.edit', ':id') }}";
editUrl = editUrl.replace(':id', id);




    $.ajax({
        url: editUrl,
        type: 'GET',
        success: function (f) {

            $('#edit-id').val(f.id);
            $('#edit-name').val(f.name);
            $('#edit-username').val(f.username);
            $('#edit-email').val(f.email);
            $('#edit-mobile').val(f.mobile);

            $('#edit-country').val(f.country);
            $('#edit-state').val(f.state);
            $('#edit-city').val(f.city);
            $('#edit-pin').val(f.pin_code);
            $('#edit-address').val(f.address);

            $('#edit-nominee_relation').val(f.nominee_relation);
            $('#edit-nominee_name').val(f.nominee_name);
            $('#edit-franchise_name').val(f.franchise_name);
            $('#edit-pan').val(f.pan_no);
            $('#edit-gst').val(f.franchise_gst);

            $('#edit-status').val(f.active_status);

            bootstrap.Offcanvas
                .getOrCreateInstance(document.getElementById('editFranchise'))
                .show();
        },
        error: function () {
            // alert('Edit AJAX failed');
        }
    });
});


$('#editForm').submit(function(e) {
    e.preventDefault();

    let id = $('#edit-id').val();

    let updateUrl = "{{ route('admin.franchise.update', ':id') }}";
    updateUrl = updateUrl.replace(':id', id);

    $.ajax({
        url: updateUrl,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',

            name: $('#edit-name').val(),
            email: $('#edit-email').val(),
            mobile: $('#edit-mobile').val(),
            country: $('#edit-country').val(),
            state: $('#edit-state').val(),
            city: $('#edit-city').val(),
            pin_code: $('#edit-pin').val(),
            address: $('#edit-address').val(),

            nominee_relation: $('#edit-nominee_relation').val(),
            nominee_name: $('#edit-nominee_name').val(),
            franchise_name: $('#edit-franchise_name').val(),
            pan_no: $('#edit-pan').val(),
            franchise_gst: $('#edit-gst').val(),

            active_status: $('#edit-status').val(),
            password: $('#edit-password').val(),
            password_confirmation: $('#edit-password_confirmation').val(),
        },
      success: function () {

    bootstrap.Offcanvas
        .getInstance(document.getElementById('editFranchise'))
        .hide();

    $('#franchiseTable').DataTable().ajax.reload(null, false);

    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Franchise details updated successfully.',
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
},

    });
});

    </script>

    <script>
        $(document).ready(function() {

            let table = $('#franchiseTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25, // ✅ DEFAULT 25 ENTRIES
                lengthMenu: [
                    [25, 50, 100],
                    [25, 50, 100]
                ],
                ajax: {
                    url: "{{ route('admin.franchise.data') }}",
                    data: function(d) {
                        d.status = $('#status').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-secondary btn-sm'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 0,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 1
                    },
                    {
                        data: 2,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 3
                    },
                    {
                        data: 4
                    },
                    {
                        data: 5
                    },
                    {
                        data: 6
                    },
                    {
                        data: 7
                    }
                ]
            });

            $('#filter').on('click', function() {
                table.draw();
            });

        });
    </script>

    

@endsection
