@extends('user.layouts.app')

@section('title', 'Reward Bonus')

@section('content')
<div class="container my-4">
    <h3 class="mb-3">🏆 Reward Bonus</h3>

    <div class="card shadow-sm">
        <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
            <p><strong>Total Left Business:</strong> {{ number_format($totalBusinessLeft, 2) }}</p>
            <p><strong>Total Right Business:</strong> {{ number_format($totalBusinessRight, 2) }}</p>
            <p><strong>Matching Business (L:R):</strong> {{ number_format($matchingBusiness, 2) }}</p>
                        </div>
            <table class="table table-bordered text-center align-middle mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Rank Name</th>
                        <th>Target ($)</th>
                        <th>Reward ($)</th>
                        <th>Achieved Business</th>
                        <th>Status</th>
                        <th>Achieved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankCriteria as $key => $rank)
                        @php
                            $achieved = in_array($rank['rank'], $userRewards);
                            $isRunning = !$achieved && $matchingBusiness >= ($rank['target'] * 0.5) && $matchingBusiness < $rank['target'];
                            $status = $achieved 
                                ? 'Completed' 
                                : ($isRunning ? 'Running' : 'Pending');
                        @endphp
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $rank['rank'] }}</td>
                            <td>${{ number_format($rank['target']) }}</td>
                            <td>${{ number_format($rank['reward']) }}</td>
                            <td>${{ number_format(min($matchingBusiness, $rank['target'])) }}</td>
                            <td>
                                @if($status === 'Completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($status === 'Running')
                                    <span class="badge bg-warning text-dark">Running</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($achieved)
                                    <span class="text-success fs-4">✔</span>
                                @else
                                    <span class="text-danger fs-4">✘</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
