<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NoticeBoard;
use DataTables;
use Illuminate\Support\Facades\Storage;

class AdminNoticeBoardController extends Controller
{
    public function index()
    {
        return view('admin.newsevents.index');
    }

    public function data(Request $request)
    {
        $data = NoticeBoard::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('img_path', function ($row) {
                return $row->img_path
                    ? '<img src="' . asset('storage/' . $row->img_path) . '" width="50">'
                    : '-';
            })
         ->editColumn('status', function ($row) {
    $status = $row->status ? 'Active' : 'Inactive';
    $class = $row->status ? 'btn-success' : 'btn-danger';
    return '<button class="btn ' . $class . ' btn-sm toggle-status" data-id="' . $row->id . '">' . $status . '</button>';
})

    ->addColumn('action', function ($row) {
    $buttons = '';

    if (auth()->user()->hasPermission('Notice Board', 'can_edit')) {
        $buttons .= '<button class="btn btn-warning btn-sm edit-notice-btn" data-id="' . $row->id . '">Edit</button>';
    }

    if (auth()->user()->hasPermission('Notice Board', 'can_delete')) {
        $buttons .= '<button class="btn btn-danger btn-sm delete-notice-btn mt-2" data-id="' . $row->id . '">Delete</button>';
    }

    return $buttons;
})


            ->rawColumns(['img_path', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:marquee,popup,carousel',
            'img_path'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img_path')) {
            $validated['img_path'] = $request->file('img_path')->store('uploads/news', 'public');
        }

        NoticeBoard::create($validated);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $notice = NoticeBoard::findOrFail($id);
        return response()->json($notice);
    }

public function update(Request $request, $id)
{
    $notice = NoticeBoard::findOrFail($id);

    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'type'        => 'required|in:marquee,popup,carousel',
        'img_path'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);


    if ($request->type === 'marquee') {

        $validated['img_path'] = null;
    }


    if ($request->hasFile('img_path')) {

        if ($notice->img_path && Storage::disk('public')->exists($notice->img_path)) {
            Storage::disk('public')->delete($notice->img_path);
        }

        $validated['img_path'] = $request->file('img_path')->store('uploads/news', 'public');
    }

    $notice->update($validated);

    return redirect()->back()->with('success', 'Notice updated successfully.');
}

public function toggleStatus(Request $request)
{
    $notice = NoticeBoard::findOrFail($request->id);
    $notice->status = !$notice->status;
    $notice->save();

    return response()->json(['success' => true, 'new_status' => $notice->status]);
}


public function destroy($id)
{
    $notice = NoticeBoard::find($id);

    if (!$notice) {
        return response()->json(['success' => false, 'message' => 'Notice not found.'], 404);
    }

    // Optional: delete associated image
    if ($notice->img_path && \Storage::disk('public')->exists($notice->img_path)) {
        \Storage::disk('public')->delete($notice->img_path);
    }

    $notice->delete();

    return response()->json(['success' => true]);
}

}
