@extends('user.layouts.app')

@section('content')
<style>
    body {
        
        font-family: 'Arial', sans-serif;
    }
    .chat-box {
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
     
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .chat-bubble {
        padding: 0.75rem 1rem;
        border-radius: 20px;
        max-width: 75%;
        position: relative;
        word-wrap: break-word;
        margin-bottom: 10px;
    }
    .chat-bubble.user {
        background-color:rgb(70, 73, 72);
        color: #fff;
        align-self: flex-end;
        text-align: right;
    }
    .chat-bubble.admin {
        background: linear-gradient(to right, #8971ea, #6c83f5);
  color: #fff;
        align-self: flex-start;
    }
    .chat-meta {
        font-size: 0.75rem;
        margin-top: 4px;
        opacity: 0.7;
    }
    .form-control {
       
        border: 1px solid #ced4da;
        /* border-radius: 20px; */
    }
    .input-group {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
        border-radius: 20px;
    }
    .ticket-status {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: bold;
    }
</style>

<div class="page-wrapper mt-4">
    <div class="container py-4">
        <div class="col-md-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div>
                    <h4 class="mb-1">Ticket #{{ $ticket->ticket }} - Chat</h4>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="ticket-status 
                        {{ $ticket->status == 0 ? 'bg-info text-white' : 
                           ($ticket->status == 1 ? 'bg-warning text-white' : 
                           ($ticket->status == 2 ? 'bg-primary text-white' : 
                           ($ticket->status == 3 ? 'bg-success text-white' : 'bg-secondary'))) }}">
                        {{ ['Open', 'Pending', 'Waiting', 'Closed'][$ticket->status] }}
                    </span>

                    @if ($ticket->status != 3)
                        <form action="{{ route('user.support.close', $ticket->id) }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Close Ticket</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Chat Box --}}
            <div class="card shadow-sm">
                <div class="chat-box d-flex flex-column">
                    @forelse ($messages as $msg)
                        <div class="d-flex {{ $msg->sender_type == 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="chat-bubble {{ $msg->sender_type == 'user' ? 'user' : 'admin' }}">
                                <div class="fw-semibold small mb-1">
                                    {{ $msg->sender_type == 'user' ? 'You' : 'Admin' }}
                                </div>
                                {{ $msg->message }}
                                <div class="chat-meta">
                                    {{ $msg->created_at->format('d M Y, h:i A') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center fw-semibold">No messages yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Reply or Closed Message --}}
            @if ($ticket->status != 3)
                <form action="{{ route('user.support.reply', $ticket->id) }}" method="POST" class="mt-4">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif 
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            @else
                <div class="alert alert-secondary mt-4 text-center">
                    This ticket is <strong>closed</strong>. You can no longer send messages.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection