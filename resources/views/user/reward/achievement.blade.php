@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="rank-header text-center mb-5">
        <h1 class="text-gradient">Rank Achievement</h1>
        <p class="lead">Track your progress through our ranking system</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Status</th>
                    <th>Strong Leg (40%)</th>
                    <th>Weak Leg (30%)</th>
                    <th>Other Legs (30%)</th>
                    <th>One-time Reward</th>
                    <th>Weekly Reward</th>
                    <th>Started</th>
                    <th>Achieved</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ranks as $rank)
                <tr class="{{ $rank['is_current_goal'] ? '' : '' }} {{ $rank['status'] === 'Achieved' ? '' : '' }}">
                    <td><strong>{{ $rank['title'] }}</strong></td>
                    <td>
                        <span class="badge 
                            {{ $rank['status'] === 'Achieved' ? 'bg-success' : ($rank['status'] === 'Pending' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ $rank['status'] }}
                        </span>
                    </td>
                    @php
                        $showRealLegs = $rank['status'] === 'Achieved' ||
                                        ($rank['status'] === 'Pending' && $rank['is_current_goal']);
                    @endphp
                    <td>
                        ${{ number_format($showRealLegs ? $rank['leg_details']['leg1'] : 0) }}/
                        {{ number_format($rank['target'] * 0.4) }}
                    </td>
                    <td>
                        ${{ number_format($showRealLegs ? $rank['leg_details']['leg2'] : 0) }}/
                        {{ number_format($rank['target'] * 0.3) }}
                    </td>
                    <td>
                        ${{ number_format($showRealLegs ? $rank['leg_details']['others'] : 0) }}/
                        {{ number_format($rank['target'] * 0.3) }}
                    </td>
                    <td>${{ number_format($rank['reward'], 0) }}</td>
                    <td>${{ number_format($rank['weekly'], 0) }}</td>
                    <td>
                        @if($rank['start_date'])
                            {{ \Carbon\Carbon::parse($rank['start_date'])->format('M d, Y') }}
                        @endif
                    </td>
                    <td>
                        @if($rank['achieved_date'])
                            {{ \Carbon\Carbon::parse($rank['achieved_date'])->format('M d, Y') }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
