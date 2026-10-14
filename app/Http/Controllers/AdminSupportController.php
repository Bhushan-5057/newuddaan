<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\SupportMessage;
use Illuminate\Support\Str;
use DataTables;
class AdminSupportController extends Controller
{
    public function Pending()
    {
  
        return view('admin.support.pending');
    }

public function supportList(Request $request)
{
    if ($request->ajax()) {
        $query = Support::with('user')->orderBy('timestamp', 'desc');

        // ✅ Filter by status
        if (!is_null($request->status) && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // ✅ Filter by username
        if (!is_null($request->username) && $request->username !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        // ✅ Filter by date range
        if (!empty($request->from) && !empty($request->to)) {
            $query->whereBetween('timestamp', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59',
            ]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => optional($row->user)->username ?? '<span class="text-muted">N/A</span>')
            ->editColumn('timestamp', fn($row) => $row->timestamp ? $row->timestamp->format('d M Y, h:i A') : '-')
            ->editColumn('status', function ($row) {
                return match ((int)$row->status) {
                    0 => '<span class="badge bg-info text-white">Open</span>',
                    1 => '<span class="badge bg-warning text-dark">Pending</span>',
                    2 => '<span class="badge bg-primary text-white">Waiting</span>',
                    3 => '<span class="badge bg-success text-white">Closed</span>',
                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })
            ->editColumn('message', fn($row) => e(Str::limit($row->message, 100)))
            ->addColumn('action', fn($row) => '<a href="' . route('admin.support.chat', $row->id) . '" class="btn btn-sm btn-primary">Reply</a>')
            ->rawColumns(['status', 'username', 'action'])
            ->make(true);
    }
}




public function showChat($id)
{
    $ticket = Support::findOrFail($id);
    $messages = SupportMessage::where('ticket_id', $id)->orderBy('created_at')->get();

    return view('admin.support.chat', compact('ticket', 'messages'));
}

public function sendReply(Request $request)
{
    $request->validate([
        'ticket_id' => 'required',
        'message' => 'required|string',
    ]);

    SupportMessage::create([
        'ticket_id' => $request->ticket_id,
        'sender_type' => 'admin',
        'message' => $request->message,
    ]);

    return redirect()->route('admin.support.chat', $request->ticket_id)->with('success', 'Reply sent.');
}


public function Approved()
{
    return view('admin.support.approved'); // adjust view path if needed
}
public function approvedList(Request $request)
{
    if ($request->ajax()) {
        $data = Support::where('reply_status', 1)->orderBy('timestamp', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('timestamp', fn($row) => $row->timestamp ? $row->timestamp->format('d M Y, h:i A') : '-')
            ->editColumn('status', fn($row) => '<span class="badge bg-success">Replied</span>')
            ->editColumn('message', fn($row) => e(Str::limit($row->message, 80)))
            ->editColumn('reply', fn($row) => '<div style="white-space: pre-wrap;">'.e(Str::limit($row->reply, 120)).'</div>')
            ->rawColumns(['status', 'reply'])
            ->make(true);
    }
}

public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|exists:support,id',
        'status' => 'required|in:0,1,2,3',
    ]);

    $ticket = Support::find($request->id);
    $ticket->status = $request->status;
    $ticket->save();

    return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
}
}
