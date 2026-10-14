<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\SupportMessage;
use App\Models\AdvanceInfo;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
class SupportController extends Controller
{
    public function index()
    {
        $userCode = auth()->user()->id;

        $openticket = Support::where('u_code', $userCode)->where('status', 0)->count();
        $pending = Support::where('u_code', $userCode)->where('status', 1)->count();
        $waiting = Support::where('u_code', $userCode)->where('status', 2)->count();
        $closed = Support::where('u_code', $userCode)->where('status', 3)->count();
        $total = Support::where('u_code', $userCode)->count();

        $latestInquiry = Support::where('u_code', $userCode)->orderBy('timestamp', 'desc')->first();


        return view('user.support.index', compact('openticket', 'pending', 'waiting', 'closed', 'total', 'latestInquiry'));
    }


public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:200',
        'email' => 'required|email|max:100',
        'message' => 'required|string',
    ]);

    // Step 1: Create Support Ticket
    $ticket = Support::create([
        'u_code' => auth()->user()->id,
        'first_name' => $request->first_name,
        'email' => $request->email,
        'contactno' => $request->contactno ?? '',
        'subject' => $request->subject ?? 'Support Inquiry',
        'message' => $request->message,
        'ticket' => strtoupper(Str::random(10)),
        'time' => now(),
        'msg_by' => 'user',
        'status' => 0,
        'reply_status' => 0,
        'timestamp' => now(),
        'updated_on' => now(),
    ]);

    // Step 2: Store First Message in SupportMessage
    SupportMessage::create([
        'ticket_id' => $ticket->id,
        'sender_type' => 'user',
        'message' => $request->message,
        'created_at' => now(),
    ]);

    return back()->with('success', 'Your support ticket has been submitted.');
}




public function supportTicketData(Request $request)
{
    if ($request->ajax()) {
        $userCode = auth()->user()->id;

        $data = Support::where('u_code', $userCode)
            ->orderBy('timestamp', 'desc');

        return DataTables::of($data)
           ->editColumn('status', function ($row) {
                $status = (int) $row->status;

                if ($status == 0) {
                    return '<span class="badge bg-info text-white">Open</span>';
                } elseif ($status == 1) {
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                } elseif ($status == 2) {
                    return '<span class="badge bg-primary text-white">Waiting</span>';
                } elseif ($status == 3) {
                    return '<span class="badge bg-success text-white">Closed</span>';
                } else {
                    return '<span class="badge bg-secondary">Unknown</span>';
                }
            })
        ->addColumn('reply', function ($row) {
    return '<a href="' . route('user.support.viewChat', $row->id) . '" class="btn btn-sm btn-info">View</a>';
})

            ->editColumn('message', function ($row) {
                return '<div style="white-space: pre-wrap;">' . e(Str::limit($row->message, 100)) . '</div>';
            })
            ->editColumn('timestamp', function ($row) {
                return $row->timestamp ? $row->timestamp->format('d M Y, h:i A') : '-';
            })
            ->rawColumns(['status', 'reply', 'message']) // Allow HTML in columns
            ->make(true);
    }
}
public function viewChat($id)
{
    $ticket = Support::where('id', $id)->where('u_code', auth()->id())->firstOrFail();

    $messages = SupportMessage::where('ticket_id', $ticket->id)->orderBy('created_at', 'asc')->get();

    return view('user.support.chat', compact('ticket', 'messages'));
}



public function sendReply(Request $request, $id)
{
    $ticket = Support::where('id', $id)
        ->where('u_code', auth()->id())
        ->firstOrFail();

    // ✅ Get max messages allowed per day from settings
    $maxPerDay = AdvanceInfo::where('label', 'max_massage_per_ticket_limit')->value('value') ?? 0;

    // ✅ Calculate today's message count for this user & ticket
    $todayStart = Carbon::today();
    $todayEnd = Carbon::now();

    $userMessageCountToday = SupportMessage::where('ticket_id', $ticket->id)
        ->where('sender_type', 'user')
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->count();

    // ✅ Check limit
    if ($maxPerDay > 0 && $userMessageCountToday >= $maxPerDay) {
        return back()->with('error', 'You have reached the daily message limit for this ticket.');
    }

    // ✅ Store message
    SupportMessage::create([
        'ticket_id' => $ticket->id,
        'sender_type' => 'user',
        'message' => $request->message,
    ]);

    // ✅ Update ticket status
    $ticket->status = 2; // Waiting
    $ticket->save();

    return redirect()->back()->with('success', 'Your reply has been sent.');
}



public function closeTicket($id)
{
    $ticket = Support::where('id', $id)->where('u_code', auth()->id())->firstOrFail();
    $ticket->status = 3; // Closed
    $ticket->save();

    return redirect()->route('user.support.index')->with('success', 'Ticket closed successfully.');
}

}
