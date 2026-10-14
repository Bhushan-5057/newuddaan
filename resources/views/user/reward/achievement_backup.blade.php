@extends('user.layouts.app')

@section('content')
<div class="container-fluid px-0 px-lg-4">
    <!-- Hero Section -->
    <div class="rank-hero bg-gradient-primary rounded-4 overflow-hidden mb-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-white mb-3">Rank Achievement11</h1>
                    <p class="lead text-white-80 mb-4">Track your progress through our ranking system and unlock rewards</p>
                    
                    @if($currentGoal)
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="rank-badge-lg bg-white text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center">
                                {{ $currentGoal['id'] }}
                            </div>
                        </div>
                        <div>
                            <h5 class="text-white mb-1">Current Goal: <span class="text-warning">{{ $currentGoal['title'] }}</span></h5>
                            <div class="progress bg-white-20" style="height: 8px; width: 200px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ $currentGoal['progress'] }}%" 
                                     aria-valuenow="{{ $currentGoal['progress'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-lg-4 text-lg-end">
                    <img src="{{ asset('images/rank-badge.png') }}" alt="Rank Badge" class="img-fluid" style="max-height: 180px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Current Goal Card -->
    @if($currentGoal)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Rank Info -->
                        <div class="col-lg-5 bg-primary text-white">
                            <div class="p-4 p-lg-5 h-100 d-flex flex-column">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="rank-badge bg-white text-primary fw-bold rounded-circle me-3">
                                        {{ $currentGoal['id'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-white mb-0">{{ $currentGoal['title'] }}</h3>
                                        <span class="text-white-80">Current Goal</span>
                                    </div>
                                </div>

                                <!-- Progress -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-80">Progress</span>
                                        <span class="text-white fw-bold">{{ number_format($currentGoal['progress'], 1) }}%</span>
                                    </div>
                                    <div class="progress bg-white-20" style="height: 10px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ $currentGoal['progress'] }}%" 
                                             aria-valuenow="{{ $currentGoal['progress'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mt-auto">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="stat-box bg-primary-dark rounded-3 p-3">
                                                <div class="text-white-80 small mb-1">Target</div>
                                                <div class="h5 mb-0 text-white">${{ number_format($currentGoal['target'], 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box bg-primary-dark rounded-3 p-3">
                                                <div class="text-white-80 small mb-1">Achieved</div>
                                                <div class="h5 mb-0 text-white">${{ number_format($currentGoal['business'], 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box bg-primary-dark rounded-3 p-3">
                                                <div class="text-white-80 small mb-1">Reward</div>
                                                <div class="h5 mb-0 text-white">${{ number_format($currentGoal['reward'], 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box bg-primary-dark rounded-3 p-3">
                                                <div class="text-white-80 small mb-1">Weekly</div>
                                                <div class="h5 mb-0 text-white">${{ number_format($currentGoal['weekly'], 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Contributions -->
                        <div class="col-lg-7">
                            <div class="p-4 p-lg-5 h-100">
                                <h4 class="mb-4">Team Contributions</h4>

                                @if(!empty($currentGoal['leg_details']['legs']))
                                    <div class="legs-container">
                                        @foreach($currentGoal['leg_details']['legs'] as $leg)
                                        <div class="leg-item mb-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <span class="fw-semibold">{{ $leg['name'] }}</span>
                                                    @if($leg['username'])
                                                        <span class="text-muted ms-2">({{ $leg['username'] }})</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="text-primary">${{ number_format($leg['business'], 2) }}</span>
                                                    <span class="text-muted">/ ${{ number_format($leg['cap'] ?? $currentGoal['target'], 2) }}</span>
                                                </div>
                                            </div>
                                            @php
                                                $legPercentage = ($leg['cap'] ?? $currentGoal['target']) > 0 
                                                    ? min(100, ($leg['business'] / ($leg['cap'] ?? $currentGoal['target'])) * 100) 
                                                    : 0;
                                            @endphp
                                            <div class="progress bg-light" style="height: 8px;">
                                                <div class="progress-bar @if($legPercentage >= 100) bg-success @else bg-primary @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $legPercentage }}%" 
                                                    aria-valuenow="{{ $legPercentage }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="total-business-display mt-4 p-3 bg-light rounded-3 text-center">
                                        <div class="text-muted small">Total Eligible Team Business</div>
                                        <div class="h3 text-primary mb-0">${{ number_format($currentGoal['business'], 2) }}</div>
                                    </div>
                                @else
                                    <div class="alert alert-info rounded-3">
                                        <i class="fas fa-info-circle me-2"></i> No team contributions yet for this rank period.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Rank Timeline -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="mb-0">Rank Progression</h3>
                </div>
                <div class="card-body">
                    <div class="rank-timeline">
                        @foreach($ranks as $rank)
                        <div class="timeline-item 
                            @if($rank['status'] == 'Achieved') achieved 
                            @elseif($rank['is_current_goal']) current 
                            @endif">
                            <div class="timeline-marker">
                                @if($rank['status'] == 'Achieved')
                                    <i class="fas fa-check-circle text-success"></i>
                                @elseif($rank['is_current_goal'])
                                    <div class="pulse-ring"></div>
                                    <i class="fas fa-bullseye text-primary"></i>
                                @else
                                    <i class="far fa-circle text-muted"></i>
                                @endif
                            </div>
                            <div class="timeline-content bg-white rounded shadow-sm">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h5 class="mb-1">{{ $rank['title'] }}</h5>
                                        <span class="badge 
                                            @if($rank['status'] == 'Achieved') bg-success 
                                            @elseif($rank['status'] == 'Pending') bg-warning text-dark 
                                            @else bg-light text-dark @endif">
                                            {{ $rank['status'] }}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted small">Target</span>
                                            <span>${{ number_format($rank['target'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted small">Achieved</span>
                                            <span>${{ number_format($rank['business'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted small">Reward</span>
                                            <span>${{ number_format($rank['reward'], 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted small mb-1">Progress</div>
                                        <div class="progress bg-light" style="height: 8px;">
                                            <div class="progress-bar 
                                                @if($rank['progress'] >= 100) bg-success 
                                                @else bg-primary @endif" 
                                                role="progressbar"
                                                style="width: {{ $rank['progress'] }}%" 
                                                aria-valuenow="{{ $rank['progress'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-end text-muted small mt-1">{{ number_format($rank['progress'], 1) }}%</div>
                                    </div>
                                    <div class="col-md-2 text-md-end">
                                        @if($rank['start_date'])
                                            <div class="text-muted small">Started</div>
                                            <div>{{ \Carbon\Carbon::parse($rank['start_date'])->format('M d, Y') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rank Information -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="mb-0">How It Works</h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="rankAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <i class="fas fa-percentage text-primary me-3"></i> Business Calculation
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#rankAccordion">
                                <div class="accordion-body">
                                    <p>Your rank progress is calculated based on:</p>
                                    <ul>
                                        <li>40% from your strongest team leg</li>
                                        <li>30% from your second strongest leg</li>
                                        <li>30% from your third strongest leg</li>
                                        <li>Additional legs contribute fully</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Additional accordion items... -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="mb-0">Rank Benefits</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-award text-primary fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>One-Time Rewards</h5>
                            <p class="mb-0">Earn substantial cash bonuses when you achieve each new rank.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-primary fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Weekly Salaries</h5>
                            <p class="mb-0">Higher ranks earn ongoing weekly payments.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-primary fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Exponential Growth</h5>
                            <p class="mb-0">Rewards increase significantly at higher ranks.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rank-hero {
        background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
    }
    
    .rank-badge {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .rank-badge-lg {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .stat-box {
        transition: all 0.3s ease;
    }
    
    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .rank-timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .rank-timeline::before {
        content: "";
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 2px;
        background: linear-gradient(to bottom, #4e54c8, #8f94fb);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    
    .timeline-content {
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .timeline-content:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .timeline-item.current .timeline-content {
        border-left: 3px solid #4e54c8;
    }
    
    .timeline-item.achieved .timeline-content {
        border-left: 3px solid #28a745;
    }
    
    .pulse-ring {
        position: absolute;
        width: 40px;
        height: 40px;
        border: 2px solid #4e54c8;
        border-radius: 50%;
        animation: pulse 2s infinite;
        opacity: 0;
    }
    
    @keyframes pulse {
        0% { transform: scale(0.8); opacity: 0.7; }
        70% { transform: scale(1.2); opacity: 0; }
        100% { opacity: 0; }
    }
    
    .text-white-80 {
        color: rgba(255,255,255,0.8);
    }
    
    .bg-white-20 {
        background-color: rgba(255,255,255,0.2);
    }
    
    .bg-primary-dark {
        background-color: #3a3fb5;
    }
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
        color: #000 !important;
    }
</style>
@endsection