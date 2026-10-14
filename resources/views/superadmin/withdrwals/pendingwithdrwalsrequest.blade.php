@extends('superadmin.layouts.app')

@section('content')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">
                        Pending Withdrawal Requests
                        <!-- <span class="badge bg-warning text-dark">
                            {{ $pendingCount ?? '0' }}
                        </span> -->
                    </h5>
                </div>

                <div class="card-datatable table-responsive p-2">
                    <table id="transactionTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>15% Booster</th>
                                <th>Charge</th>
                                <th>Payable</th>
                                <th>Account Details</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
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
        $(function () {
            var table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('superadmin.withdrwalstransactionsrequest.list') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tx_user', name: 'tx_user' },
                    { data: 'amount', name: 'amount' },
                    { data: 'booster_preview', name: 'booster_preview', orderable: false, searchable: false }, // ✅ 15% booster
                    { data: 'tx_charge', name: 'tx_charge' },
                    { data: 'payable_amount', name: 'payable_amount' },
                    { data: 'account_details', name: 'account_details', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'date', name: 'date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

    </script>
@endsection