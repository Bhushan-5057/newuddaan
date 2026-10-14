@extends('user.layouts.app')

@section('title', 'Royalty Bonus')

@section('content')
<style>
    .royalty-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        background: linear-gradient(145deg, #1e1e26, #2a2a35);
    }
    .royalty-card:hover {
        transform: translateY(-5px);
    }
    .rank-badge {
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .business-stat {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #ffc107;
    }
    .progress {
        height: 8px;
        background-color: #3e3e4f;
        border-radius: 10px;
    }
    .text-gold {
        color: #ffd700;
    }
    .table-custom {
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table-custom tr {
        background: #252530;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .table-custom td, .table-custom th {
        border: none;
        padding: 15px;
    }
    .table-custom tr td:first-child { border-radius: 10px 0 0 10px; }
    .table-custom tr td:last-child { border-radius: 0 10px 10px 0; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-white"> Royalty and Rewards Achievement</h4>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class=" card business-stat shadow-sm">
                <p class="text-muted mb-1 uppercase small fw-bold">Left Team Business</p>
                <h3 class="fw-bold text-white">${{ number_format($leftBusiness) }}</h3>
                <div class="progress mt-2">
                    <div class="progress-bar bg-success" style="width: 70%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card business-stat shadow-sm" style="border-left-color: #0dcaf0;">
                <p class="text-muted mb-1 uppercase small fw-bold">Right Team Business</p>
                <h3 class="fw-bold text-white">${{ number_format($rightBusiness) }}</h3>
                <div class="progress mt-2">
                    <div class="progress-bar bg-info" style="width: 50%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-custom text-white align-middle">
                <thead>
                    <tr class="text-muted small uppercase">
                        <th>Rank</th>
                        <th>Target (L/R)</th>
                        <th>Daily Reward</th>
                        <th>One-Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($royaltyRanks as $rank)
                    @php 
                        $isAchieved = $rank['status'] === 'Achieved';
                        $percent = min(100, ($leftBusiness / $rank['business']) * 100); 
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <span class="fs-4">{{ $isAchieved ? '⭐' : '🔒' }}</span>
                                </div>
                                <div>
                                    <span class="fw-bold d-block">{{ $rank['rank'] }}</span>
                                    <small class="text-muted">Level {{ $loop->iteration }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gold fw-semibold">${{ number_format($rank['business']) }}</span>
                            <div class="progress mt-1" style="width: 100px; height: 4px;">
                                <div class="progress-bar {{ $isAchieved ? 'bg-success' : 'bg-warning' }}" style="width: {{ $isAchieved ? '100' : $percent }}%"></div>
                            </div>
                        </td>
                        <td>
                            <span class="text-white">${{ number_format($rank['daily']) }}</span>
                            <small class="d-block text-muted">for 100 days</small>
                        </td>
                        <td>
                            <span class="badge bg-soft-light border border-secondary text-white">${{ number_format($rank['reward']) }}</span>
                        </td>
                        <td>
                            @if ($isAchieved)
                                <span class="rank-badge bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i> Completed
                                </span>
                            @else
                                <span class="rank-badge bg-secondary text-light">
                                    In Progress
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No royalty ranks available yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection