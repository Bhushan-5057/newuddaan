@extends('superadmin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
table.dataTable th.no-sort::after {
    display: none !important;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom">
            <h5 class="card-title mb-0">Mail Template List</h5>
            <a href="{{ route('superadmin.mailtemplates.create') }}" class="btn btn-success btn-sm">
                <i class="bx bx-plus"></i> Create Mail Template
            </a>
        </div>
        <div class="card-datatable table-responsive p-2">
            <table class="table datatables-mailtemplates">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th> {{-- Add this --}}
                        <th>Subject</th>
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Use event delegation because forms are dynamically loaded by DataTables
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.classList.contains('delete-template-form')) {
            e.preventDefault(); // stop normal submission

            Swal.fire({
                title: 'Are you sure?',
                text: "Delete this template?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form if confirmed
                }
            });
        }
    });
});

$(document).ready(function() {
    $('.datatables-mailtemplates').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.mailtemplates.data') }}",
        columns: [{
                data: 'id'
            },
            {
                data: 'name'
            },
            {
                data: 'slug'
            }, // Add this
            {
                data: 'subject'
            },
            {
                data: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>
@endsection