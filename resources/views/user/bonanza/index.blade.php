@extends('user.layouts.app')
@section('title', 'My Bonanza Achievements')

@section('content')
<div class="bonanza-container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-header-card bg-gradient-dark-blue p-4 rounded-lg shadow">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="text-white font-weight-bold mb-2">Tour Rewards</h1>
                        <p class="text-light mb-0">Track your progress and celebrate your achievements</p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <div class="achievement-counter">
                            <div class="counter-label text-light opacity-75">Total Achieved</div>
                            <div class="counter-value display-4 font-weight-bold text-white">{{ $achievements->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-dark-blue-800 p-3 rounded">
                            <div class="stat-icon text-info">
                                <i class="fas fa-bolt fa-2x"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number text-white">{{ $activeBonanzas->count() }}</div>
                                <div class="stat-label text-light opacity-75">Active Campaigns</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-dark-blue-800 p-3 rounded">
                            <div class="stat-icon text-warning">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number text-white">{{ $achievements->where('status', 'completed')->count() }}</div>
                                <div class="stat-label text-light opacity-75">Completed</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-dark-blue-800 p-3 rounded">
                            <div class="stat-icon text-success">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number text-white">{{ $totalRewards ?? 0 }}</div>
                                <div class="stat-label text-light opacity-75">Total Rewards</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Active Bonanzas -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-dark-blue-900 text-white rounded-top-lg d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-primary-light mr-3">
                            <i class="fas fa-bolt text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Active Bonanzas</h5>
                            <small class="text-light opacity-75">Live campaigns you're participating in</small>
                        </div>
                    </div>
                    <span class="live-badge pulse-animation">
                        <span class="pulse-dot"></span>
                        LIVE NOW
                    </span>
                </div>

                <div class="card-body bg-dark-blue-850 p-0">
                    @if($activeBonanzas->isEmpty())
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-calendar-plus fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-white mb-2">No Active Campaigns</h5>
                            <p class="text-light opacity-75 mb-0">New opportunities are coming soon. Stay tuned!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="bg-dark-blue-900">
                                    <tr>
                                        <th class="border-0 py-3">
                                            <span class="text-light">Bonanza Title</span>
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            <span class="text-light">Self ID</span>
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            <span class="text-light">Directs</span>
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            <span class="text-light">Matching</span>
                                        </th>
                                        <th class="border-0 py-3">
                                            <span class="text-light">Timeline</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeBonanzas as $bonanza)
                                        <tr class="border-bottom border-dark-blue-800">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="campaign-icon mr-3">
                                                        <i class="fas fa-flag text-info"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-white font-weight-600">{{ $bonanza->trip_name }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="text-success font-weight-bold">${{ number_format($bonanza->self_id, 2) }}</span>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="text-success font-weight-bold">{{ $bonanza->direct }}</span>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="text-warning font-weight-bold">${{ number_format($bonanza->matching_business, 2) }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="timeline-progress">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <small class="text-light">{{ \Carbon\Carbon::parse($bonanza->start_date)->format('d M') }}</small>
                                                        <small class="text-light">{{ \Carbon\Carbon::parse($bonanza->end_date)->format('d M') }}</small>
                                                    </div>
                                                    <div class="progress bg-dark-blue-800">
                                                        <div class="progress-bar bg-gradient-primary" 
                                                             style="width: {{ calculateProgress($bonanza->start_date, $bonanza->end_date) }}%"
                                                             role="progressbar">
                                                        </div>
                                                    </div>
                                                    <small class="d-block text-light opacity-75 mt-1">
                                                        {{ \Carbon\Carbon::parse($bonanza->end_date)->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Hall of Fame -->
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-dark-blue-900 text-white rounded-top-lg">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-warning-light mr-3">
                            <i class="fas fa-trophy text-warning"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Hall of Fame</h5>
                            <small class="text-light opacity-75">Your achievement showcase</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body bg-dark-blue-850 p-0">
                    @if($achievements->isEmpty())
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-trophy fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-white mb-2">No Achievements Yet</h5>
                            <p class="text-light opacity-75 mb-0">Complete bonanzas to fill your trophy cabinet</p>
                        </div>
                    @else
                        <div class="achievement-showcase">
                            @foreach($achievements as $index => $ach)
                                <div class="achievement-card {{ $index < 3 ? 'highlight' : '' }}">
                                    <div class="achievement-rank">
                                        <div class="rank-badge {{ $index < 3 ? 'rank-' . ($index + 1) : '' }}">
                                            #{{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="achievement-content">
                                        <div class="achievement-title">
                                            <h6 class="mb-0 text-white">{{ $ach->bonanza->trip_name }}</h6>
                                            <small class="text-light opacity-75">
                                                {{ \Carbon\Carbon::parse($ach->created_at)->format('d M Y') }}
                                            </small>
                                        </div>
                                        <div class="achievement-meta">
                                            @if($ach->reward_amount)
                                                <span class="reward-badge">
                                                    <i class="fas fa-gift mr-1"></i>
                                                    ${{ number_format($ach->reward_amount, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="achievement-status">
                                        <i class="fas fa-check-circle fa-lg {{ $index < 3 ? 'text-gold' : 'text-success' }}"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --dark-blue-900: #0a192f;
    --dark-blue-850: #112240;
    --dark-blue-800: #233554;
    --dark-blue-700: #2d4263;
    --primary-blue: #2563eb;
    --primary-light: #3b82f6;
    --gradient-dark-blue: linear-gradient(135deg, #0a192f 0%, #1e3a8a 100%);
    --gradient-primary: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
}

/* Base Styles */
.bonanza-container {
    min-height: 80vh;
}

.text-white { color: #ffffff !important; }
.text-light { color: #e2e8f0 !important; }
.bg-dark-blue-900 { background-color: var(--dark-blue-900) !important; }
.bg-dark-blue-850 { background-color: var(--dark-blue-850) !important; }
.bg-dark-blue-800 { background-color: var(--dark-blue-800) !important; }

/* Header Card */
.dashboard-header-card {
    background: var(--gradient-dark-blue);
    border: 1px solid var(--dark-blue-700);
}

/* Stat Cards */
.stat-card {
    border: 1px solid var(--dark-blue-700);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--primary-blue);
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.2);
}

.stat-icon {
    margin-right: 15px;
    opacity: 0.9;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Card Styling */
.card {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--dark-blue-700);
}

.card-header {
    border-bottom: 1px solid var(--dark-blue-700);
    padding: 1.25rem 1.5rem;
}

.icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-light { background: rgba(59, 130, 246, 0.1) !important; }
.bg-warning-light { background: rgba(251, 191, 36, 0.1) !important; }

/* Live Badge */
.live-badge {
    background: rgba(220, 38, 38, 0.2);
    color: #f87171;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    border: 1px solid rgba(220, 38, 38, 0.3);
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    margin-right: 8px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Table Styling */
.table-dark {
    background: transparent;
    color: #e2e8f0;
}

.table-dark thead th {
    border-bottom: 2px solid var(--dark-blue-700);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
}

.table-dark tbody tr {
    transition: background-color 0.2s ease;
}

.table-dark tbody tr:hover {
    background-color: var(--dark-blue-800);
}

.direct-count-badge {
    background: var(--gradient-primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.875rem;
    min-width: 36px;
    display: inline-block;
    text-align: center;
}

/* Progress Bar */
.progress {
    height: 6px;
    border-radius: 3px;
    background-color: var(--dark-blue-800);
}

.progress-bar {
    background: var(--gradient-primary);
    border-radius: 3px;
}

/* Achievement Showcase */
.achievement-showcase {
    max-height: 500px;
    overflow-y: auto;
}

.achievement-card {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--dark-blue-800);
    transition: all 0.3s ease;
}

.achievement-card.highlight {
    background: rgba(37, 99, 235, 0.05);
}

.achievement-card:hover {
    background: var(--dark-blue-800);
}

.achievement-rank {
    margin-right: 15px;
}

.rank-badge {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--dark-blue-700);
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.rank-badge.rank-1 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.rank-badge.rank-2 {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    color: white;
}

.rank-badge.rank-3 {
    background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
    color: white;
}

.achievement-content {
    flex: 1;
}

.achievement-title h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.reward-badge {
    background: rgba(34, 197, 94, 0.1);
    color: #4ade80;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.achievement-status .text-gold {
    color: #fbbf24;
}

.text-success { color: #10b981 !important; }
.text-warning { color: #f59e0b !important; }
.text-info { color: #0ea5e9 !important; }
.text-primary { color: #3b82f6 !important; }

/* Scrollbar Styling */
.achievement-showcase::-webkit-scrollbar {
    width: 6px;
}

.achievement-showcase::-webkit-scrollbar-track {
    background: var(--dark-blue-800);
}

.achievement-showcase::-webkit-scrollbar-thumb {
    background: var(--primary-blue);
    border-radius: 3px;
}

/* Empty States */
.empty-state {
    padding: 3rem 1.5rem;
}

.empty-icon {
    opacity: 0.5;
}

/* Campaign Icon */
.campaign-icon {
    width: 40px;
    height: 40px;
    background: rgba(14, 165, 233, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0ea5e9;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .dashboard-header-card {
        padding: 1.5rem !important;
    }
    
    .achievement-card {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .direct-count-badge {
        font-size: 0.75rem;
        padding: 2px 8px;
    }
}
</style>

@php
function calculateProgress($start, $end) {
    $start = \Carbon\Carbon::parse($start);
    $end = \Carbon\Carbon::parse($end);
    $now = \Carbon\Carbon::now();
    
    if ($now->gte($end)) return 100;
    if ($now->lte($start)) return 0;
    
    $totalDuration = $end->diffInSeconds($start);
    $elapsedDuration = $now->diffInSeconds($start);
    
    return min(100, round(($elapsedDuration / $totalDuration) * 100));
}
@endphp

@endsection