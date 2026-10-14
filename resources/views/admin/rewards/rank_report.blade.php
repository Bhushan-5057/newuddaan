@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Filter Panel --}}
    <div class="card shadow mb-4">
        <div class="card-header">
            <h4 class="mb-0">Filter Reports</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rank') }}" class="row g-3">
                
                {{-- તારીખના ક્વિક ઓપ્શન્સ --}}
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Date Filter</label>
                    <select name="date_range" id="date_range" class="form-control">
                        <option value="all" {{ $dateRange == 'all' ? 'selected' : '' }}>All Data</option>
                        <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $dateRange == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ $dateRange == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="last_week" {{ $dateRange == 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $dateRange == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Date</option>
                    </select>
                </div>

                {{-- રેન્ક ફિલ્ટર --}}
                <div class="col-md-3">
                    <label for="rank" class="form-label">Select Rank</label>
                    <select name="rank" id="rank" class="form-control">
                        <option value="">-- All Ranks --</option>
                        @foreach($distinctRanks as $rank)
                            <option value="{{ $rank }}" {{ request('rank') == $rank ? 'selected' : '' }}>
                                {{ $rank }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Custom Date બોક્સ (જ્યારે 'custom' સિલેક્ટ હશે ત્યારે જ દેખાશે) --}}
                <div class="col-md-3 custom-date-field" style="display: {{ $dateRange == 'custom' ? 'block' : 'none' }};">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate ? $fromDate->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-3 custom-date-field" style="display: {{ $dateRange == 'custom' ? 'block' : 'none' }};">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate ? $toDate->format('Y-m-d') : '') }}">
                </div>

                {{-- બટનો --}}
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2 w-100">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <a href="{{ route('admin.rank') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total Achievers</h5>
                    <h2>{{ $totalAchievers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total Rank Bonus</h5>
                    <h2>₹ {{ number_format($totalBonus,2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Rank Reward Report</h4>
            <div>
                <span class="badge bg-info">
                    Showing {{ $rankRewards->firstItem() ?? 0 }} to {{ $rankRewards->lastItem() ?? 0 }} of {{ $rankRewards->total() }} records
                </span>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Rank</th>
                        <th>Bonus</th>
                        <th>Reward</th>
                        <th>Bonanza</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankRewards as $key=>$reward)
                        <tr>
                            <td>{{ $rankRewards->firstItem() + $key }}</td>
                            <td>{{ $reward->user->username ?? '-' }}</td>
                            <td>{{ $reward->rank }}</td>
                            <td>₹ {{ number_format($reward->bonus,2) }}</td>
                            <td>{{ $reward->reward }}</td>
                            <td>{{ $reward->bonanza }}</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($reward->status) }}</span>
                            </td>
                            <td>{{ $reward->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $rankRewards->firstItem() ?? 0 }} to {{ $rankRewards->lastItem() ?? 0 }} of {{ $rankRewards->total() }} entries
                </div>
                <div>
                    {{ $rankRewards->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- jQuery Code: ડ્રોપડાઉન બદલાય ત્યારે તારીખના બોક્સ શો/હાઈડ કરવા માટે --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#date_range').on('change', function() {
        if ($(this).val() === 'custom') {
            $('.custom-date-field').fadeIn();
        } else {
            $('.custom-date-field').fadeOut();
            $('#from_date').val('');
            $('#to_date').val('');
        }
    });
});
</script>

@endsection