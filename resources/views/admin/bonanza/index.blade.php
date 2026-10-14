@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
  <h4>Bonanza List</h4>
  
  <a href="{{ route('admin.bonanza.create') }}" class="btn btn-primary mb-3">Create Bonanza</a>
  
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Trip Name</th>
        <th>Self ID</th>
        <th>Direct Users</th>
        <th>Matching Business ($)</th>
        <th>Duration</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bonanzas as $i => $b)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $b->trip_name }}</td>
          <td>{{ $b->self_id }}</td>
          <td>{{ $b->direct }}</td>
          <td>${{ number_format($b->matching_business, 2) }}</td>
          <td>{{ $b->start_date }} → {{ $b->end_date }}</td>
          <td>
            <a href="{{ route('admin.bonanza.edit', $b->id) }}" class="btn btn-sm btn-info">Edit</a>
            <form action="{{ route('admin.bonanza.destroy', $b->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this bonanza?')">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center">No Bonanzas Found</td></tr>
      @endforelse
    </tbody>
  </table>
  
  {{ $bonanzas->links() }}
</div>
@endsection