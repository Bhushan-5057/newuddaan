@extends('user.layouts.app')

@section('title', 'Dream Car Bonus')

@section('content')
<div class="container my-4">
    <h3 class="mb-4">Your Dream Car Bonus ({{ $startDate->format('F Y') }})</h3>

    <!-- User Data Table -->
    <table class="table table-bordered table-striped mb-4">
        <thead class="table-primary">
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>KYC Approved</th>
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
                    @if($userData['status'] == 'Active')
                        <span class="badge bg-success">{{ $userData['status'] }}</span>
                    @elseif($userData['status'] == 'Reinvest Required')
                        <span class="badge bg-warning text-dark">{{ $userData['status'] }}</span>
                    @else
                        <span class="badge bg-danger">{{ $userData['status'] }}</span>
                    @endif
                </td>
                <td>
                    @if($userData['kyc_approved'])
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>{{ $userData['pairsMatched'] }}</td>
                <td>{{ $userData['pairsRequired'] }}</td>
                <td>${{ number_format($userData['bonusEarned'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Bonus Slab Table -->
    <h4 class="mb-2">Bonus Slabs</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Accumulated Pairs</th>
                <th>Monthly Bonus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bonusSlabs as $pair => $bonus)
                <tr>
                    <td>{{ $pair }}</td>
                    <td>${{ number_format($bonus, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Note -->
    <div class="alert alert-info mt-3">
        Bonus is calculated based on pairs achieved between 
        <strong>{{ $startDate->format('d M Y') }}</strong> to 
        <strong>{{ $endDate->format('d M Y') }}</strong>.
    </div>
</div>
@endsection
