@extends('superadmin.layouts.app')

@section('content')
<div class="container py-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Ticket #{{ $ticket->ticket }} - Chat</h4>

    <select id="ticket-status" class="form-select w-auto" data-id="{{ $ticket->id }}">
        <option value="0" {{ $ticket->status === 0 ? 'selected' : '' }}>Open</option>
        <option value="1" {{ $ticket->status === 1 ? 'selected' : '' }}>Pending</option>
        <option value="2" {{ $ticket->status === 2 ? 'selected' : '' }}>Waiting</option>
        <option value="3" {{ $ticket->status === 3 ? 'selected' : '' }}>Closed</option>
    </select>
</div>

    <div class="card mb-4">
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            @foreach ($messages as $msg)
                <div class="mb-3 {{ $msg->sender_type === 'admin' ? 'text-end' : 'text-start' }}">
                    <div class="d-inline-block p-2 rounded {{ $msg->sender_type === 'admin' ? 'bg-primary text-white' : 'bg-light' }}">
                        <small><strong>{{ ucfirst($msg->sender_type) }}:</strong></small><br>
                        {{ $msg->message }}
                        <div><small class="text-muted">{{ $msg->created_at->format('d M, h:i A') }}</small></div>
                    </div>
                </div>
            @endforeach 
        </div>
    </div>

    <form action="{{ route('superadmin.support.chat.send') }}" method="POST">
        @csrf
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
        <div class="mb-3">
            <textarea name="message" rows="4" class="form-control" placeholder="Type your reply..." required></textarea>
        </div>
        <button class="btn btn-success">Send Reply</button>
        <a href="{{ route('superadmin.support.pending') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection


@section('scripts')
<script>
    $('#ticket-status').on('change', function () {
        let ticketId = $(this).data('id');
        let newStatus = $(this).val();

        $.ajax({
            url: '{{ route("superadmin.support.updateStatus") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: ticketId,
                status: newStatus
            },
            success: function (res) {
                if (res.success) {
                    alert('Ticket status updated!');
                }
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    });
</script>

@endsection