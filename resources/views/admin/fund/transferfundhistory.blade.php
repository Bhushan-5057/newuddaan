@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">



            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">Transfer Fund Transaction List</h5>
                </div>



            <div class="d-flex justify-content-start mb-2">
                <button class="btn btn-primary btn-sm" onclick="exportTransferFundCSV()">
                    Export CSV
                </button>
            </div>


                <div class="card-datatable table-responsive p-2">
                    <table id="transactionTable" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Wallet</th>
                                <th>Amount</th>
                                <th>Debit/Credit</th>
                                <th>Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.transferfundtransactions.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'tx_type',
                        name: 'tx_type'
                    },
                    {
                        data: 'wallet_type',
                        name: 'wallet_type'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'debit_credit',
                        name: 'debit_credit'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                ]
            });

        });
    </script>
    <script>
        function exportTransferFundCSV() {
            window.location = "{{ route('admin.transferfundtransactions.export') }}";
        }
    </script>
@endsection
