@extends('user.layouts.app')

@section('title', 'Leadership Circle Bonus')

@section('content')
    <div class="container my-4">
        <h3 class="mb-2">Leadership Circle Bonus</h3>
       

        <!-- User Bonus Status -->
        <table class="table table-bordered table-striped mb-4">
            <thead class="table-dark">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Pairs Achieved</th>
                    <th>Pairs Required</th>
                    <th>Bonus Earned</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $userData['id'] }}</td>
                    <td>{{ $userData['name'] }}</td>
                    <td>
                        @if ($userData['status'] == 'Active')
                            <span class="badge bg-success">{{ $userData['status'] }}</span>
                        @elseif($userData['status'] == 'Reinvest Required')
                            <span class="badge bg-warning text-dark">{{ $userData['status'] }}</span>
                        @else
                            <span class="badge bg-danger">{{ $userData['status'] }}</span>
                        @endif
                    </td>
                    <td>{{ $userData['pairsMatched'] }}</td>
                    <td>
                        @if ($userData['pairsRequired'] > 0)
                            <span class="text-danger">{{ $userData['pairsRequired'] }} more</span>
                        @else
                            <span class="text-success">Achieved</span>
                        @endif
                    </td>
                    <td><strong>${{ $userData['bonusEarned'] }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Bonus Slabs -->
        <h4 class="mb-3">Leadership Bonus Slabs</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Required Pairs</th>
                    <th>Monthly Bonus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bonusSlabs as $pair => $bonus)
                    <tr @if ($userData['pairsMatched'] >= $pair && $bonus == $userData['bonusEarned']) class="table-success" @endif>
                        <td>{{ $pair }}</td>
                        <td>${{ $bonus }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

          <!-- Note -->
    <div class="alert alert-info mt-3">
        Bonus is calculated based on pairs achieved between 
        <strong>{{ isset($startDate) ? $startDate->format('d M Y') : '-' }}</strong> to
            <strong>{{ isset($endDate) ? $endDate->format('d M Y') : '-' }}</strong>.
    </div>


    </div>
@endsection
