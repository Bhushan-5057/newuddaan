@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <h4 class="mb-3">🎉 Bonanza Achievers</h4>
    <div class="card shadow-sm rounded-3">
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Bonanza</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Achieved On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($achievers as $key => $achiever)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $achiever->bonanza->trip_name ?? 'N/A' }}</td>
                        <td>{{ $achiever->user->username ?? 'N/A' }}</td>
                        <td>{{ $achiever->user->email ?? 'N/A' }}</td>
                        <td>{{ $achiever->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No achievers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
