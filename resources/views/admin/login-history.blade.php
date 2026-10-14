@extends('admin.layouts.app')

@section('title', 'Admin Login History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Logins</h6>
                                    <h2 class="mt-2 mb-0">{{ number_format($stats['total_logins']) }}</h2>
                                </div>
                                <i class="fas fa-sign-in-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Today's Logins</h6>
                                    <h2 class="mt-2 mb-0">{{ number_format($stats['today_logins']) }}</h2>
                                </div>
                                <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Active Sessions</h6>
                                    <h2 class="mt-2 mb-0">{{ number_format($stats['active_sessions']) }}</h2>
                                </div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Unique IPs</h6>
                                    <h2 class="mt-2 mb-0">{{ number_format($stats['unique_ips']) }}</h2>
                                </div>
                                <i class="fas fa-network-wired fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Login Activity (Last 7 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="loginChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Top Active Users</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($topUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-circle fa-2x me-2"></i>
                                            <strong>{{ $user->user->name ?? 'Unknown' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->user->email ?? '' }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $user->login_count }} logins</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card with Filters -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-history me-2"></i> Admin Login History
                        </h4>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <a href="{{ route('admin.login.history.export', request()->all()) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="filter-section mb-4 p-3 bg-light rounded">
                        <form method="GET" action="{{ route('admin.login.history') }}" id="filterForm">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="search" 
                                           value="{{ $search }}"
                                           placeholder="Search by email, IP, location...">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">User</label>
                                    <select class="form-select" name="user_id">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $userFilter == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="date_from" 
                                           value="{{ $dateFrom }}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="date_to" 
                                           value="{{ $dateTo }}">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="btn-group w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter me-1"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('admin.login.history') }}" class="btn btn-secondary">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Login History Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="loginHistoryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">User</th>
                                    <th width="20%">IP Address</th>
                                    <th width="15%">Location</th>
                                    <th width="20%">Login Time</th>
                                    <th width="15%">Duration</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginHistories as $history)
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <div class="avatar-initial rounded-circle bg-primary">
                                                    {{ substr($history->user->name ?? 'N/A', 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $history->user->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $history->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $history->ip_address }}</code>
                                        <br>
                                        <a href="https://whatismyipaddress.com/ip/{{ $history->ip_address }}" 
                                           target="_blank" class="small">
                                            <i class="fas fa-external-link-alt"></i> Lookup
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $history->location ?? 'Unknown' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-clock text-info me-1"></i>
                                        {{ $history->login_at->format('M d, Y H:i:s') }}
                                        <br>
                                        <small class="text-muted">{{ $history->login_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($history->logout_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-hourglass-half"></i>
                                                {{ gmdate('H:i:s', $history->session_duration) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Logout: {{ $history->logout_at->format('H:i:s') }}
                                            </small>
                                        @elseif(!$history->logout_at && $history->login_at->diffInMinutes(now()) < 30)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-circle text-success"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal{{ $history->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No login records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $loginHistories->appends(request()->query())->links() }}
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        Showing <strong>{{ $loginHistories->firstItem() ?? 0 }}</strong> to 
                        <strong>{{ $loginHistories->lastItem() ?? 0 }}</strong> of 
                        <strong>{{ $loginHistories->total() }}</strong> entries
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modals -->
@foreach($loginHistories as $history)
<div class="modal fade" id="detailsModal{{ $history->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Login Details #{{ $history->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>User Information</strong>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $history->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $history->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Username:</th>
                                        <td>{{ $history->username ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td>{{ ucfirst($history->user->role ?? 'N/A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>Session Information</strong>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Login Time:</th>
                                        <td>{{ $history->login_at->format('F j, Y g:i:s A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Logout Time:</th>
                                        <td>{{ $history->logout_at ? $history->logout_at->format('F j, Y g:i:s A') : 'Active' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Duration:</th>
                                        <td>{{ $history->session_duration ? gmdate('H:i:s', $history->session_duration) : 'Active' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <strong>Technical Details</strong>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="20%">IP Address:</th>
                                        <td><code>{{ $history->ip_address }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Location:</th>
                                        <td>{{ $history->location ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>User Agent:</th>
                                        <td><small>{{ $history->user_agent }}</small></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
    }
    .avatar {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-initial {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .filter-section {
        border-radius: 10px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">


@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#loginHistoryTable').DataTable({
        "pageLength": 25,
        "order": [[0, "desc"]],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)"
        }
    });
    
    // Auto-submit form on filter change
    $('#filterForm select, #filterForm input[type="date"]').on('change', function() {
        $('#filterForm').submit();
    });
    
    // Export functionality
    $('#exportBtn').on('click', function() {
        window.location.href = "{{ route('admin.login.history.export') }}?" + $('#filterForm').serialize();
    });
});

// Login Chart
const ctx = document.getElementById('loginChart').getContext('2d');
const loginChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($hourlyActivity->pluck('hour')->map(function($hour) { 
            return date('g A', mktime($hour, 0, 0)); 
        })) !!},
        datasets: [{
            label: 'Logins',
            data: {!! json_encode($hourlyActivity->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Login Activity by Hour (Today)'
            }
        }
    }
});
</script>
@endsection