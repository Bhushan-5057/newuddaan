@extends('admin.layouts.app')



@section('content')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row g-4 mb-4">

            <div class="card p-3">

                <div class="card-header d-flex justify-content-between align-items-center border-bottom">

                    <h5 class="card-title mb-0">Cancelled Withdrawal Requests List</h5>

                </div>

                <div class="mb-2">
                    <button class="btn btn-primary btn-sm" onclick="exportApprovedCSV()">
                        Export CSV
                    </button>
                </div>





                <div class="card-datatable table-responsive p-2">

                    <table id="transactionTable" class="table table-striped">

                        <thead>

                            <tr>

                                <th>S No.</th>

                                <th>Tx User</th>

                                <th>Action</th>

                                <th>Amount</th>

                                <th>Tx Charge</th>

                                <th>Payable Amount</th>

                                <th>User Wallet Address</th>

                                <th>Status</th>

                                <th>Reason</th>

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
        @if (session('success'))

            Swal.fire({

                icon: 'success',

                title: 'Success',

                text: '{{ session('success') }}',

                toast: true,

                position: 'bottom-end',

                showConfirmButton: false,

                timer: 3000,

                timerProgressBar: true

            });
        @endif



        @if ($errors->any())

            Swal.fire({

                icon: 'error',

                title: 'Validation Error',

                html: `{!! implode('<br>', $errors->all()) !!}`

            });
        @endif


        $(function() {
            $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Export CSV',
                    className: 'btn btn-primary btn-sm'
                }],
                ajax: "{{ route('admin.cancelledwithdrwalsrequest.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false
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
                        data: 'user_wallet_address'
                    },
                    {
                        data: 'status',
                        orderable: false
                    },
                    {
                        data: 'reason',
                        orderable: false
                    },
                    {
                        data: 'date'
                    }
                ]
            });
        });
    </script>

    <script>
        function exportApprovedCSV() {
            window.location = "{{ route('admin.cancelled.withdraw.csv') }}";
        }
    </script>
@endsection
