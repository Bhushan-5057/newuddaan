@extends('admin.layouts.app')
@section('content')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <div class="container-fluid py-4">
        <div class="col-sm-3 col-12 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ $totalAchievers }}</h2>
                    <span class="text-muted">Total Royalty Achievers</span>
                </div>
            </div>
        </div>

        <div class="card p-4 shadow">
            <h5>Royalty Achievers List</h5>
            <div class="table-responsive">
                <table id="royaltyAchieversTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Username</th>
                            <th>Rank</th>
                            <th>Royalty %</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#royaltyAchieversTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.royalty.achievers.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_info',
                        name: 'user_info'
                    },
                    {
                        data: 'rank',
                        name: 'rank'
                    },
                    {
                        data: 'royalty',
                        name: 'royalty'
                    },
                    {
                        data: 'status_label',
                        name: 'status_label',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    }
                ]
            });
        });
    </script>
@endsection
