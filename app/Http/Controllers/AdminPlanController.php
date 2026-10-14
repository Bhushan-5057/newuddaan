<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\Plan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomFieldValue;

class AdminPlanController extends Controller
{
    public function index()
    {
        $customFields = CustomField::where('module_name', 'plan')->orderBy('position')->get();
        return view('admin.plan.list', compact('customFields'));
    }

    public function list(Request $request)
    {
        // Select new fields: package_name, rank, status, custom_fields
        $data = Plan::select('id', 'package_name', 'rank', 'status', 'custom_fields');

        // Load custom fields definitions
        $customFieldDefs = CustomField::where('module_name', 'plan')->get()->keyBy('id');

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
                $editBtn = '';
                $deleteBtn = '';

                // Check permission for edit
                if (auth()->user()->hasPermission('Plan List', 'can_edit')) {
                    $editBtn = '<button type="button" class="btn btn-sm btn-primary edit-plan" data-id="' . $row->id . '">Edit</button>';
                }

                // Check permission for delete
                if (auth()->user()->hasPermission('Plan List', 'can_delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-sm btn-danger delete-plan" data-id="' . $row->id . '">Delete</button>';
                }
                return '<button class="btn btn-sm btn-warning view-custom-fields" data-id="'.$row->id.'" data-fields=\''.$customFieldsJson.'\' data-package="'.e($row->package_name).'" data-rank="'.e($row->rank).'" data-status="'.$row->status.'">View</button> ' . $editBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // Validate static fields
        $request->validate([
            'package_name' => 'required|string|max:255',
            'rank' => 'required',
            'status' => 'required|in:1,0',
        ]);

        $customData = [];

        if ($request->has('custom')) {
            foreach ($request->custom as $fieldId => $value) {
                $finalValue = is_array($value) ? implode(',', $value) : $value;

                CustomFieldValue::create([
                    'user_id'           => auth()->id(),
                    'custom_field_ids'  => $fieldId,
                    'value'             => $finalValue,
                    'module_name'       => 'plan',
                ]);

                $customData[$fieldId] = $finalValue;
            }
        }

        $plan = new Plan();
        $plan->package_name = $request->package_name;
        $plan->rank = $request->rank;
        $plan->status = $request->status;
        // $plan->added_on = now();
        $plan->custom_fields = json_encode($customData);
        $plan->save();

        return redirect()->back()->with('success', 'Plan created successfully.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);

        return response()->json([
            'id' => $plan->id,
            'package_name' => $plan->package_name,
            'rank' => $plan->rank,
            'status' => $plan->status,
            'custom_fields' => json_decode($plan->custom_fields, true),
        ]);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->package_name = $request->package_name;
        $plan->rank = $request->rank;
        $plan->status = $request->status;

        if ($request->has('custom')) {
            $plan->custom_fields = json_encode($request->custom);
        }

        $plan->save();

        return redirect()->route('admin.plan.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json(['success' => true]);
    }
}
