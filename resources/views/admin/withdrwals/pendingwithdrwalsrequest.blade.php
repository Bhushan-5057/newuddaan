@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">

<style>
    td { vertical-align: middle !important; }
    .copy-text { font-size: 13px; }
    .btn-xs { padding: 2px 6px; font-size: 11px; }
</style>

@php
    $currency = company_info('currency', '₹');
@endphp

<div class="container-xxl flex-grow-1 container-p-y">

    {{-- SUMMARY CARDS --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="totalAmount">{{ $currency }} 0.00</h4>
                    <small>Total Amount</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="totalPaidAmount">{{ $currency }} 0.00</h4>
                    <small>Total Payout Amount</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="totalCount">0</h4>
                    <small>Total Entries</small>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card p-3 mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" id="from_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" id="to_date" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100" id="filterBtn">Filter</button>
                <button class="btn btn-secondary w-100" id="resetBtn">Reset</button>
            </div>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="mb-2 d-flex gap-2">
        <button class="btn btn-success btn-sm" onclick="bulkApprove()">Bulk Approve</button>
        <button class="btn btn-danger btn-sm" onclick="bulkReject()">Bulk Reject</button>
        <button class="btn btn-primary btn-sm" onclick="exportCSV()">Export Selected CSV</button>
    </div>

    {{-- TABLE --}}
    <div class="card p-2">
        <table id="transactionTable" class="table table-striped w-100">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Amount</th>
                    <th>Charge</th>
                    <th>Payable</th>
                     <th>Account Details</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
let table;
let selectedIds = new Set();

$(document).ready(function () {

    table = $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,

        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],

        scrollY: '60vh',
        scrollX: true,
        scrollCollapse: true,
        fixedHeader: true,

        dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"f>>' +
             '<"row"<"col-12"B>>' +
             '<"row"<"col-12"tr>>' +
             '<"row mt-2"<"col-md-5"i><"col-md-7"p>>',

        buttons: [
            { extend: 'excel', title: 'Pending_Withdrawals_Report' },
            { extend: 'pdf',   title: 'Pending_Withdrawals_Report' },
            { extend: 'print', title: 'Pending Withdrawals Report' }
        ],

        ajax: {
            url: "{{ route('admin.withdrwalstransactionsrequest.list') }}",
            data: function (d) {
                d.from_date = $('#from_date').val();
                d.to_date   = $('#to_date').val();
            }
        },

        columns: [
            { data: 'checkbox', orderable:false, searchable:false },
            { data: 'DT_RowIndex', orderable:false },
            { data: 'tx_user' },
            { data: 'action', orderable:false },
            { data: 'amount' },
            { data: 'tx_charge' },
            { data: 'payable_amount' },
            { data: 'account_details', orderable:false, searchable:false },
            { data: 'status', orderable:false },
            { data: 'date' }
        ],

        drawCallback: function (settings) {
            if (settings.json) {
                let currency = settings.json.currency ?? '';
                $('#totalAmount').text(currency + ' ' + settings.json.filteredAmount);
                $('#totalPaidAmount').text(currency + ' ' + settings.json.filteredPaidAmount);
                $('#totalCount').text(settings.json.filteredCount);
            }

            // restore checked rows
            $('.rowCheckbox').each(function () {
                if (selectedIds.has($(this).val())) {
                    $(this).prop('checked', true);
                }
            });

            updateSelectAllState();
        }
    });

    // FILTER
    $('#filterBtn').on('click', function () {
        table.ajax.reload();
    });

    $('#resetBtn').on('click', function () {
        $('#from_date,#to_date').val('');
        table.ajax.reload();
    });

});

/* ================= SELECT ALL ================= */
$(document).on('change', '#selectAll', function () {
    let checked = this.checked;

    $('.rowCheckbox').each(function () {
        $(this).prop('checked', checked);
        checked ? selectedIds.add($(this).val()) : selectedIds.delete($(this).val());
    });
});

/* ================= SINGLE CHECKBOX ================= */
$(document).on('change', '.rowCheckbox', function () {
    let id = $(this).val();
    this.checked ? selectedIds.add(id) : selectedIds.delete(id);
    updateSelectAllState();
});

function updateSelectAllState() {
    let total = $('.rowCheckbox').length;
    let checked = $('.rowCheckbox:checked').length;
    $('#selectAll').prop('checked', total > 0 && total === checked);
}

</script>

    <script>

          window.getSelectedIds = function() {
                return Array.from(selectedIds);
            };
        function bulkApprove() {
            let ids = getSelectedIds();
            if (!ids.length) return alert('Select records');

            $.post("{{ route('admin.withdrwalsrequest.bulkApprove') }}", {
                _token: "{{ csrf_token() }}",
                ids
            }, () => $('#transactionTable').DataTable().ajax.reload());
        }

        function bulkReject() {
            let ids = getSelectedIds();
            let reason = prompt('Enter reject reason');
            if (!ids.length || !reason) return;

            $.post("{{ route('admin.withdrwalsrequest.bulkReject') }}", {
                _token: "{{ csrf_token() }}",
                ids,
                reason
            }, () => $('#transactionTable').DataTable().ajax.reload());
        }

        function exportCSV() {
            let ids = getSelectedIds();
            if (!ids.length) return alert('Select records');

            window.location =
                "{{ route('admin.withdrwalsrequest.export') }}?ids=" + ids.join(',');
        }


        $(document).on('click', '.copy-btn', function() {
            navigator.clipboard.writeText(
                $(this).siblings('.copy-text').data('copy')
            );
            alert('Copied');
        });
    </script>

    <script>
   
    </script>
@endsection
