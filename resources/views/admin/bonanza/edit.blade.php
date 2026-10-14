@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
  <h4>Edit Bonanza</h4>
  
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  <form method="POST" action="{{ route('admin.bonanza.update', $bonanza->id) }}">
    @csrf @method('PUT')
    
    <div class="mb-3">
      <label>Trip Name</label>
      <input name="trip_name" class="form-control" value="{{ $bonanza->trip_name }}" required>
    </div>
    
    <div class="mb-3">
      <label>Self ID</label>
      <input type="number" name="self_id" class="form-control" value="{{ $bonanza->self_id }}">
    </div>
    
    <div class="mb-3">
      <label>Direct Users</label>
      <input type="number" step="1" name="direct" class="form-control" value="{{ $bonanza->direct }}" required>
    </div>
    
    <div class="mb-3">
      <label>Matching Business ($)</label>
      <input type="number" step="0.01" name="matching_business" class="form-control" value="{{ $bonanza->matching_business }}" required>
    </div>
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ $bonanza->start_date }}" required>
      </div>
      <div class="col-md-6 mb-3">
        <label>End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ $bonanza->end_date }}" required>
      </div>
    </div>
    
    <button class="btn btn-success">Update</button>
    <a href="{{ route('admin.bonanza.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection