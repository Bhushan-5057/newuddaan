@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">

            <div class="card p-3">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">Admin Power Business History</h5>
                </div>

                <div class="d-flex justify-content-start mb-2 mt-2">
                    <button class="btn btn-primary btn-sm" onclick="exportPowerBusinessCSV()">
                        Export CSV
                    </button>
                </div>

                <div class="card-datatable table-responsive p-2">
                    <table id="powerBusinessTable" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Business Type</th>
                                <th>Binary Side Position</th>
                                <th>BV Amount</th>
                                <th>Date Granted</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#powerBusinessTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.powerhistory.list') }}",
                columns: [
                    {
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
                        data: 'matching_type',
                        name: 'matching_type'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'bv_amount',
                        name: 'bv_amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
        function exportPowerBusinessCSV() {
            window.location = "{{ route('admin.powerhistory.export') }}";
        }
    </script>
@endsection