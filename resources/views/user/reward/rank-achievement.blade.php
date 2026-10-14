@extends('user.layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">🏆 Rank, Reward & Royalty Progress</h2>

    <div class="row">
        <!-- Running Rank -->
        <div class="col-lg-6">
            @if ($runningRank)
            <div class="card mb-4 shadow">
                <div class="card-header bg-info text-white">
                    🔄 Current Running Rank - <strong>{{ $runningRank['title'] }}</strong>
                </div>
                <div class="card-body">
                    <p><strong>Reward:</strong> ${{ number_format($runningRank['reward']) }}</p>
                    <p><strong>Progress:</strong></p>

                    @foreach ($runningRank['progress'] as $label => $bar)
                    <p class="mb-1">
                        {{ $label }} ({{ $bar['percent'] }}%)
                        @if (isset($bar['achieved']) && isset($bar['required']))
                        – {{ is_numeric($bar['achieved']) ? '$' . number_format($bar['achieved'], 2) : $bar['achieved'] }}
                        /
                        {{ is_numeric($bar['required']) ? '$' . number_format($bar['required'], 2) : $bar['required'] }}
                        @endif
                    </p>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped bg-{{ $bar['color'] }}"
                            role="progressbar"
                            style="width: {{ $bar['percent'] }}%">
                            {{ $bar['percent'] }}%
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Achieved Ranks -->
        <div class="col-lg-6">
            <div class="card mb-4 shadow">
                <div class="card-header bg-success text-white">
                    ✅ Achieved Ranks
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table  mb-0">
                        <thead class="table bg-primary text-white">
                            <tr>
                                <th>Rank</th>
                                <th>Reward</th>
                                <th>Date Achieved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($achievedRanks as $rank)
                            <tr>
                                <td><strong>{{ $rank->rank_name }}</strong></td>
                                <td>${{ number_format($rank->reward, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($rank->achieved_at)->format('d M, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No ranks achieved yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pending Ranks -->
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark">
                    ⏳ Pending Ranks & Rewards
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="bg-warning text-dark">
                                <tr class="bg-primary text-white">
                                    <th>Rank</th>
                                    <th>Reward</th>
                                    <th>Team Business</th>
                                    <th>Strong Leg</th>
                                    <th>Weaker Legs</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($pendingRanks as $rank)
                                <tr class="text-center">
                                    <td>{{ $rank['title'] }}</td>
                                    <td>${{ number_format($rank['reward']) }}</td>
                                    <td>
                                        ${{ number_format($teamData['total_team_business'] ?? 0, 2) }} / ${{ number_format($rank['team_business'], 2) }}
                                        @if (($teamData['total_team_business'] ?? 0) >= $rank['team_business'])
                                        ✅
                                        @endif
                                    </td>
                                    <td>
                                        ${{ number_format($teamData['strong_leg']['business'] ?? 0, 2) }} / ${{ number_format($rank['stronge_business'], 2) }}
                                        @if (($teamData['strong_leg']['business'] ?? 0) >= $rank['stronge_business'])
                                        ✅
                                        @endif
                                    </td>
                                    <td>
                                        ${{ number_format(collect($teamData['other_legs'])->sum('business'), 2) }} / ${{ number_format($rank['other_legs'], 2) }}
                                        @if (collect($teamData['other_legs'])->sum('business') >= $rank['other_legs'])
                                        ✅
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($achievedRankTitles) && in_array($rank['title'], $achievedRankTitles))
                                        <span class="badge bg-success">Achieved</span>
                                        @elseif ($runningRank['title'] == $rank['title'])
                                        <span class="badge bg-warning text-dark">Running</span>
                                        @else
                                        <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Royalty Section -->
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    👑 Royalty Achievements
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Royalty Rank</th>
                                    <th>Required Team</th>
                                    <th>Royalty Income</th>
                                    <th>Achieved Team</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse ($royaltyRanks as $royalty)
                                <tr class="text-center">
                                    <td>{{ $royalty['title'] }}</td>
                                    <td>{{ number_format($royalty['team']) }}</td>
                                    <td>${{ number_format($royalty['royalty']) }}</td>
                                    <td>{{ number_format($teamData['total_team_count'] ?? 0) }}</td>
                                    <td>
                                        @if(in_array($royalty['title'], $achievedRoyaltyTitles ?? []))
                                            <span class="badge bg-success">Achieved</span>
                                        @elseif(($teamData['total_team_count'] ?? 0) >= $royalty['team'])
                                            <span class="badge bg-warning text-dark">Running</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No royalty data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
