@extends('admin.layouts.app')



@section('content')
    <style>
        td {
            vertical-align: middle !important;
        }

        .copy-text {
            font-size: 13px;
        }

        .btn-xs {
            padding: 2px 6px;
            font-size: 11px;
        }
    </style>


    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row g-4 mb-4">

            <div class="card p-3">

                <div class="card-header d-flex justify-content-between align-items-center border-bottom">

                    <h5 class="card-title mb-0">Pending Withdrawal Requests List





                        <span class="badge bg-warning text-dark">

                            {{ $pendingCount ?? '0' }}

                        </span>

                    </h5>






                </div>

                
                    <div class="mb-2 d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="bulkApprove()">Bulk Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="bulkReject()">Bulk Reject</button>
                        <button class="btn btn-primary btn-sm" onclick="exportCSV()">Export CSV</button>
                    </div>




                <div class="card-datatable table-responsive p-2">

                    <table id="transactionTable" class="table table-striped">

                        <thead>

                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>

                                <th>S No.</th>

                                <th>Tx User</th>

                                <th>Action</th>

                                <th>Amount</th>

                                <th>Tx Charge</th>

                                <th>Payable Amount</th>

                                <th>User Wallet Address</th>

                                <th>Status</th>

                                <th>Date</th>

                            </tr>

                        </thead>

                    </table>



                </div>

            </div>

        </div>

    </div>
@endsection



@section('scripts')
    <!-- jQuery (required by DataTables) -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <!-- DataTables JS -->

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        let selectedIds = new Set();

        /* ================= DATATABLE INIT ================= */
        $(function() {

            let table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.withdrwalstransactionsrequest.list') }}",
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tx_user'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'tx_charge'
                    },
                    {
                        data: 'payable_amount'
                    },
                    {
                        data: 'user_wallet_address',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        orderable: false
                    },
                    {
                        data: 'date'
                    }
                ],
                drawCallback: function() {
                    // restore checked state after redraw
                    $('.rowCheckbox').each(function() {
                        if (selectedIds.has($(this).val())) {
                            $(this).prop('checked', true);
                        }
                    });

                    updateSelectAll();
                }
            });

            /* ================= SELECT ALL ================= */
            $(document).on('change', '#selectAll', function() {
                let checked = this.checked;

                $('.rowCheckbox').each(function() {
                    $(this).prop('checked', checked);

                    if (checked) {
                        selectedIds.add($(this).val());
                    } else {
                        selectedIds.delete($(this).val());
                    }
                });
            });

            /* ================= SINGLE CHECKBOX ================= */
            $(document).on('change', '.rowCheckbox', function() {
                let id = $(this).val();

                if (this.checked) {
                    selectedIds.add(id);
                } else {
                    selectedIds.delete(id);
                }

                updateSelectAll();
            });

            function updateSelectAll() {
                let total = $('.rowCheckbox').length;
                let checked = $('.rowCheckbox:checked').length;

                $('#selectAll').prop('checked', total > 0 && total === checked);
            }

            /* ================= EXPORT / BULK ================= */
            window.getSelectedIds = function() {
                return Array.from(selectedIds);
            };

        });
    </script>

    <script>
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
@endsection
