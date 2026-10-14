<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\CompanyInfo;

use Yajra\DataTables\Facades\DataTables;
class AdminCompanyInfoController extends Controller
{
    public function index()
    {
        return view('admin.company_info.index'); // Create this Blade view
    }

public function getData(Request $request)
{
       $query = CompanyInfo::where('admin_status', 1)->select(['id', 'title', 'label', 'value', 'type', 'status','added_on']);

    return DataTables::of($query)
        ->addColumn('status', function ($row) {
            return $row->status == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';
        })
       ->rawColumns(['status'])
        ->addColumn('status_raw', fn($row) => $row->status)


        ->make(true);
}


public function update(Request $request)
{
    $data = CompanyInfo::findOrFail($request->id);

    $data->title = $request->title;
    $data->label = $request->label;
    $data->type = $request->type;
    $data->status = $request->status;


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


}