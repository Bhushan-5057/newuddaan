@extends('franchise.layouts.app')
@section('content')
    <style>
        .proof-thumb {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            transition: transform .3s ease;
            cursor: pointer;
        }

        .proof-thumb:hover {
            transform: scale(2.8);
            z-index: 10;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Request Fund</h5>
                        <form action="{{ route('franchise.fund.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Amount</label>
                                <input type="number" name="amount" class="form-control" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Payment Proof (Screenshot)</label>
                                <input type="file" name="payment_screenshot" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Remark</label>
                                <textarea name="remark" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Request</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 fw-bold">Request History</div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Proof</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $row)
                                    <tr>
                                        <td>{{ $row->created_at->format('d M Y') }}</td>

                                        <td class="fw-bold">₹{{ number_format($row->amount, 2) }}</td>

                                        <td>
                                            @if ($row->img)
                                                <a href="{{ asset('storage/' . $row->img) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $row->img) }}" class="proof-thumb"
                                                        title="Click to open">
                                                </a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div>{{ $row->remark ?? '-' }}</div>

                                            @if ($row->status == 2 && $row->reject_reason)
                                                <div class="text-danger small">
                                                    <strong>Reason:</strong> {{ $row->reject_reason }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($row->status == 0)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($row->status == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
