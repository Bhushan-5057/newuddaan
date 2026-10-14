@extends('admin.layouts.app')

@section('content')
    <style>
        .proof-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            transition: transform .3s ease;
        }

        .proof-img:hover {
            transform: scale(3);
            z-index: 10;
        }
    </style>
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }
    </style>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Fund Requests</div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                              <th >Action</th>
                         
                            <th>Franchise</th>
                            <th>Amount</th>
                            <th>Proof</th>
                            <th>Remark</th>
                            <th>Status</th>
                               <th>Date</th>
                          
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($requests as $row)
                            <tr>

                                

                                <td>
                                    @if ($row->status == 0)
                                        <form action="{{ route('admin.franchise.fund.approve', $row->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <button class="btn btn-danger btn-sm" onclick="rejectFund({{ $row->id }})">
                                            Reject
                                        </button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                           
                                <td>
                                    <strong>{{ $row->franchise->name ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $row->u_code }}</small>
                                </td>

                                <td class="fw-bold"> {{ company_info('currency', '$') }} {{ number_format($row->amount, 2) }}</td>

                                <td>
                                    @if ($row->img)
                                        <a href="{{ asset('storage/' . $row->img) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $row->img) }}" class="proof-img">
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $row->remark ?? '-' }}
                                    @if ($row->status == 2)
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

                                     <td>{{ $row->created_at->format('d M Y') }}</td>

                            </tr>


                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No fund requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
function rejectFund(id) {
    Swal.fire({
        title: 'Reject Fund Request',
        input: 'textarea',
        inputLabel: 'Reject Reason',
        inputPlaceholder: 'Enter reject reason...',
        inputAttributes: {
            'aria-label': 'Reject reason'
        },
        showCancelButton: true,
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        inputValidator: (value) => {
            if (!value) {
                return 'Reject reason is required!';
            }
        }
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.franchise.fund.reject', ':id') }}".replace(':id', id),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    reject_reason: result.value
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejected',
                        text: 'Fund request rejected successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong'
                    });
                }
            });

        }
    });
}
</script>
@endsection
