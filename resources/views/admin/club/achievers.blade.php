@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-gem text-primary me-2"></i> Yearly Club Achievers (Diamond x4)</h5>
            <span class="badge bg-light-info text-dark">Period: {{ date('M Y', strtotime($startDate)) }} - {{ date('M Y', strtotime($endDate)) }}</span>
        </div>
        
        <form action="{{ route('admin.club.distribute') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row mb-4 bg-light p-3 rounded-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Enter Amount per User (5% Profit Share)</label>
                        <input type="number" name="amount" class="form-control" placeholder="e.g. 5000" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-paper-plane me-1"></i> Distribute Income</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center border">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Diamond Count</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($achievers as $user)
                            <tr>
                                <td><input type="checkbox" name="achiever_ids[]" value="{{ $user->id }}" class="user-checkbox"></td>
                                <td class="fw-bold">{{ $user->username }}</td>
                                <td>{{ $user->name }}</td>
                                <td><span class="badge bg-success">{{ $user->rank_history_count }} Times</span></td>
                                <td><span class="badge bg-soft-success text-success">Qualified</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-muted">No club achievers found for this period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // બધા યુઝર્સને એકસાથે સિલેક્ટ કરવા માટે
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection