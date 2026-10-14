@extends('admin.layouts.app')

@section('content')

<style>
    .table:not(.table-dark) thead:not(.table-dark) th {
  
    color: #000 !important;
}
</style>
<div class="container-fluid py-4">

    {{-- FILTER --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="fw-bold">From Date</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="fw-bold">To Date</label>
                    <input type="date" name="to_date" value="{{ $toDate }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.rank') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <p class="text-muted mt-2">
                Showing data from
                <b>{{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}</b>
                to
                <b>{{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</b>
            </p>
        </div>
    </div>

    {{-- RANK POOL SUMMARY --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Rank-wise 2% CTO & Point Breakup
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Rank Pool (2%)</th>
                        <th>Total Points</th>
                        <th>1 Point Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankSummary as $rank => $data)
                    <tr>
                        <td class="fw-bold">{{ $rank }}</td>
                        <td>{{ company_info('currancy', '₹') }} {{ number_format($data['rank_pool'],2) }}</td>
                        <td>{{ $data['total_points'] }}</td>
                        <td>{{ company_info('currancy', '₹') }} {{ number_format($data['point_value'],4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total CTO</h6>
                <h4 class="fw-bold text-primary">
                    ₹ {{ number_format($overallCto, 2) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">2% Rank Pool</h6>
                <h4 class="fw-bold text-success">
                    ₹ {{ number_format($overallRankPool, 2) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">
                    Total Rank Income
                    @if($isRunningMonth)
                        (Predicted)
                    @else
                        (Credited)
                    @endif
                </h6>
                <h4 class="fw-bold text-warning">
                    ₹ {{ number_format($totalRankIncome, 2) }}
                </h4>
            </div>
        </div>
    </div>
</div>

    {{-- USER DETAILS --}}
    <div class="card">
        <div class="card-header fw-bold">
            User Rank Income Details
            @if($isRunningMonth)
                <span class="badge bg-warning ms-2">Predicted</span>
            @else
                <span class="badge bg-success ms-2">Credited</span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-striped text-center mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>User</th>
                        <th>Matching BV</th>
                        <th>Points</th>
                        <th>Rank-wise Income</th>
                        <th>Total Rank Income</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                    <tr>
                        <td class="fw-bold">{{ $row['username'] }}</td>
                        <td>{{ number_format($row['matching_bv']) }}</td>
                        <td>{{ $row['points'] }}</td>
                        <td>
                            @foreach($row['rank_earning'] as $re)
                                <div class="border rounded p-1 mb-1">
                                    <b>{{ $re['rank'] }}</b>,   Points: {{ $re['points'] }} ,  Amount: {{ company_info('currancy', '₹') }} {{ number_format($re['amount'],2) }} </b>
                                </div>
                            @endforeach
                        </td>
                        <td class="fw-bold text-success">
                            {{ company_info('currancy', '₹') }} {{ number_format($row['rank_income'],2) }}
                            <br>
                            <small class="text-muted">{{ $row['income_type'] }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">
                            No data found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection