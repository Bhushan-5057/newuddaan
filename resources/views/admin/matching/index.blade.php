@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h3>Manual Matching Income Distribution</h3>

        {{-- Filter Form --}}
        <form action="{{ route('admin.matching.filter') }}" method="GET" class="row g-3 mb-4">
            @csrf
            <div class="col-md-3">
                <label>Month & Year</label>
                <input type="month" name="month" class="form-control" required value="{{ $month ?? '' }}">
            </div>
            <div class="col-md-3">
                <label>Required Pairs</label>
                <input type="number" name="pairs" class="form-control" min="1" required
                    value="{{ $requiredPairs ?? '' }}">
            </div>
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="date_from" class="form-control" required value="{{ $dateFrom ?? '' }}">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="date_to" class="form-control" required value="{{ $dateTo ?? '' }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Show Eligible Users</button>
            </div>
        </form>

        {{-- Eligible Users List --}}
        @isset($eligibleUsers)
            <div class="card mt-4">
                <div class="card-header">
                    Eligible Users (Month: {{ $month }}, Required Pairs: {{ $requiredPairs }})
                </div>

                <table class="table table-bordered mb-3">
                    <thead>
                        <tr>
                            {{-- <th>User ID</th> --}}
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Total Pairs ({{ $dateFrom->format('Y-m-d') }} - {{ $dateTo->format('Y-m-d') }})</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eligibleUsers as $row)
                            <tr>
                                {{-- <td>{{ $row['user']->id }}</td> --}}
                                <td>{{ $row['user']->name }}</td>
                                <td>{{ $row['user']->username }}</td>
                                <td>{{ $row['user']->email }}</td>
                                <td>{{ $row['pair_count'] }}</td>
                                <td>
                                    <span class="badge {{ $row['status'] == 'Achieved' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $row['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Users Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <form action="{{ route('admin.matching.distribute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pairs" value="{{ $requiredPairs }}">
                    <input type="hidden" name="from_date" value="{{ $dateFrom->format('Y-m-d') }}">
                    <input type="hidden" name="to_date" value="{{ $dateTo->format('Y-m-d') }}">
                    @foreach ($userIds as $id)
                        <input type="hidden" name="user_ids[]" value="{{ $id }}">
                    @endforeach
                    {{-- <button type="submit" class="btn btn-success">Distribute Income to Eligible Users</button> --}}
                </form>

            </div>
        @endisset
    </div>
@endsection
