<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\CompanyInfo;

use Yajra\DataTables\Facades\DataTables;
class CompanyInfoController extends Controller
{
    public function index()
    {
        return view('superadmin.company_info.index'); // Create this Blade view
    }




public function getData(Request $request)
{
    $query = CompanyInfo::select(['id', 'title', 'label', 'value', 'type', 'status', 'admin_status', 'added_on']);

    return DataTables::of($query)
        ->addColumn('status', function ($row) {
            return $row->status == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';
        })
        ->addColumn('admin_status', function ($row) {
            return $row->admin_status == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-warning">Pending</span>';
        })
        ->rawColumns(['status', 'admin_status'])
        ->addColumn('status_raw', fn($row) => $row->status)
->addColumn('admin_status_raw', fn($row) => $row->admin_status)

        ->make(true);
}


public function update(Request $request)
{
    $data = CompanyInfo::findOrFail($request->id);

    $data->title = $request->title;
    $data->label = $request->label;
    $data->type = $request->type;
    $data->status = $request->status;
    $data->admin_status = $request->admin_status;

    // File handling
    if ($request->type === 'file' && $request->hasFile('value')) {
        $file = $request->file('value');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $data->value = $filename;
    } elseif (!in_array($request->type, ['file', 'checkbox', 'radio', 'select'])) {
        $data->value = $request->value;
    }

    $data->save();

    return redirect()->back()->with('success', 'Updated successfully');
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'label' => 'nullable|string|max:255',
        'value' => 'required|string|max:1000',
        'type' => 'required|in:text,select,radio,checkbox,file',
        'status' => 'required|boolean',
    ]);

    CompanyInfo::create([
        'title' => $request->title,
        'label' => $request->label,
        'value' => $request->value,
        'type' => $request->type,
        'status' => $request->status,
    ]);

    return response()->json(['success' => true, 'message' => 'Company info created successfully.']);
}


}