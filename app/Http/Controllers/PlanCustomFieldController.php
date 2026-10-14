<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;

class PlanCustomFieldController extends Controller
{
    // Show all custom fields for 'plan' module
    public function index()
    {
        $fields = CustomField::where('module_name', 'plan')
                             ->orderBy('position')
                             ->get();

        return view('superadmin.plan.custom_fields', compact('fields'));
    }

    // Store new custom field
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,email,number,date,checkbox,select,radio',
            'options' => 'nullable|string',
        ]);

        // Find the max current position
        $maxPosition = CustomField::where('module_name', 'plan')->max('position');

        CustomField::create([
            'name'        => $request->name,
            'type'        => $request->type,
            'required'    => $request->has('required'),
            'options'     => $request->options,
            'module_name' => 'plan',
            'position'    => $maxPosition + 1,
        ]);

        return redirect()->route('plan.custom_fields.index')
                         ->with('success', 'Custom field added successfully.');
    }

    // Update existing custom field
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,email,number,date,checkbox,select,radio',
            'options' => 'nullable|string',
        ]);

        $field = CustomField::findOrFail($id);

        $field->update([
            'name'     => $request->name,
            'type'     => $request->type,
            'required' => $request->has('required'),
            'options'  => $request->options,
        ]);

        return redirect()->route('plan.custom_fields.index')
                         ->with('success', 'Field updated successfully.');
    }

    // Delete custom field
    public function destroy($id)
    {
        $field = CustomField::findOrFail($id);
        $field->delete();

        return redirect()->route('plan.custom_fields.index')
                         ->with('success', 'Field deleted successfully.');
    }

    // Reorder fields (called via AJAX)
   public function reorder(Request $request)
{
    foreach ($request->order as $item) {
        CustomField::where('id', $item['id'])
                   ->where('module_name', 'plan')
                   ->update(['position' => $item['position']]);
    }

    return response()->json(['status' => 'success']);
}

}
