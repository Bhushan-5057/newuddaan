<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\PinDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomFieldValue;

class PinDetailsController extends Controller
{
  
public function index()
{
    $customFields = CustomField::where('module_name', 'pin_details')->orderBy('position')->get();
    return view('superadmin.pin_details.list', compact('customFields'));
}



public function list(Request $request)
{
    $data = PinDetail::select('id', 'pin_type', 'status', 'custom_fields');

    // Load all custom fields with module_name = 'pin_details' once
    $customFieldDefs = CustomField::where('module_name', 'pin_details')->get()->keyBy('id');

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row) {
            return $row->status ? 'Active' : 'Inactive';
        })
        ->addColumn('actions', function($row) use ($customFieldDefs) {
            $customFields = json_decode($row->custom_fields, true) ?: [];

            // Map ID to name and value
            $mappedFields = [];
            foreach ($customFields as $fieldId => $value) {
                $fieldName = isset($customFieldDefs[$fieldId]) ? $customFieldDefs[$fieldId]->name : $fieldId;
                $mappedFields[$fieldName] = $value;
            }

            $customFieldsJson = htmlspecialchars(json_encode($mappedFields), ENT_QUOTES, 'UTF-8');
$editBtn = '<button type="button" class="btn btn-sm btn-primary edit-pin" data-id="'.$row->id.'">Edit</button>';
    $deleteBtn = '<button type="button" class="btn btn-sm btn-danger delete-pin" data-id="'.$row->id.'">Delete</button>';

            return '<button class="btn btn-sm btn-warning view-custom-fields" data-id="'.$row->id.'" data-fields=\''.$customFieldsJson.'\' data-pin="'.e($row->pin_type).'" data-status="'.$row->status.'">View</button>' . ' ' .  $editBtn . ' ' . $deleteBtn;
        })
        ->rawColumns(['actions'])
        ->make(true);
     

}



public function store(Request $request)
{
    // Validate static fields
    $request->validate([
        'pin_type' => 'required|string|max:255',
        'status' => 'required|in:1,0',
    ]);

    $customData = [];

    if ($request->has('custom')) {
        foreach ($request->custom as $fieldId => $value) {
            $finalValue = is_array($value) ? implode(',', $value) : $value;

            CustomFieldValue::create([
                'user_id'           => auth()->id(), // or pass from form if needed
                'custom_field_ids'  => $fieldId,
                'value'             => $finalValue,
                'module_name'       => 'pin_details',
            ]);

            $customData[$fieldId] = $finalValue;
        }
    }

    $pin = new PinDetail();
    $pin->pin_type = $request->pin_type;
    $pin->status = $request->status;
    $pin->added_on = now();
    $pin->custom_fields = json_encode($customData);
    $pin->save();

    return redirect()->back()->with('success', 'Pin Detail created successfully.');
}








public function edit($id)
{
    $pinDetail = PinDetail::findOrFail($id);
    // Return JSON with pin details, including decoding custom_fields JSON
    return response()->json([
        'id' => $pinDetail->id,
        'pin_type' => $pinDetail->pin_type,
        'status' => $pinDetail->status,
        'custom_fields' => json_decode($pinDetail->custom_fields, true),
    ]);
}

public function update(Request $request, $id)
{
    $pinDetail = PinDetail::findOrFail($id);
    $pinDetail->pin_type = $request->pin_type;
    $pinDetail->status = $request->status;

    // Collect dynamic custom fields from $request->custom, encode as JSON
    if ($request->has('custom')) {
        $pinDetail->custom_fields = json_encode($request->custom);
    }

    $pinDetail->save();

    return redirect()->route('superadmin.pindetails.index')->with('success', 'Pin Detail updated successfully.');
}

public function destroy($id)
{
    $pinDetail = PinDetail::findOrFail($id);
    $pinDetail->delete();

    return response()->json(['success' => true]);
}







}
