@extends('admin.layouts.app')

@section('content')
    <style>
        .copy-text {
            font-size: 13px;
            word-break: break-all;
        }

        .btn-xs {
            padding: 2px 6px;
            font-size: 11px;
        }

        td {
            vertical-align: middle !important;
        }
    </style>

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">Pending Fund Request List

                        <span class="badge bg-warning text-dark">
                            {{ $pendingfund ?? '0' }}
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
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>

                                <th>S No.</th>
                                <th>Tx User</th>
                                <th>Debit/Credit</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Type</th>
                                <th>Wallet Address</th>
                                <th>Transaction Hash</th>
                                <th>Payment Slip</th>
                                <th>Status</th>
                                <th>Action</th>
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
        $(document).on('click', '.copy-btn', function() {
            let text = $(this).closest('div').find('.copy-text').data('copy');

            navigator.clipboard.writeText(text).then(() => {
                alert('Copied');
            });
        });
    </script>

    <script>
        $(function() {
            var table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.transferfundtransactionsrequest.list') }}",
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="rowCheckbox" value="${data}">`;
                        }
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
                        data: 'debit_credit'
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'payment_type'
                    },
                    {
                        data: 'tx_type'
                    },
                    {
                        data: 'cripto_address'
                    },
                    {
                        data: 'utr_number'
                    },
                    {
                        data: 'payment_slip',
                        orderable: false,
                        searchable: false
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
                    },
                    {
                        data: 'date'
                    }
                ]

            });



        });
    </script>
    <script>
        $('#selectAll').on('click', function() {
            $('.rowCheckbox').prop('checked', this.checked);
        });

        $(document).on('change', '.rowCheckbox', function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
        });

        function getSelectedIds() {
            let ids = [];
            $('.rowCheckbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }


        function bulkApprove() {
            let ids = getSelectedIds();

            if (ids.length === 0) {
                alert('Please select at least one record');
                return;
            }

            if (!confirm('Approve selected fund requests?')) return;

            $.post("{{ route('admin.transferfundtransactionsrequest.bulkApprove') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids
            }, function(res) {
                if (res.success) {
                    alert('Approved successfully');
                    $('#transactionTable').DataTable().ajax.reload();
                }
            });
        }

        function bulkReject() {
            let ids = getSelectedIds();
            let reason = prompt('Enter rejection reason');

            if (!ids.length || !reason) return;

            $.post("{{ route('admin.transferfundtransactionsrequest.bulkReject') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids,
                reason: reason
            }, function(res) {
                alert('Rejected successfully');
                $('#transactionTable').DataTable().ajax.reload();
            });
        }

        function exportCSV() {
            let ids = getSelectedIds();

            if (!ids.length) {
                alert('Select records to export');
                return;
            }

            window.location.href =
                "{{ route('admin.transferfundtransactionsrequest.export') }}?ids=" + ids.join(',');
        }
    </script>
@endsection
