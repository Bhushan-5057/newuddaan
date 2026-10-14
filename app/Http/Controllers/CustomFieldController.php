<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;

class CustomFieldController extends Controller
{
    // Show all custom fields
    public function index()
    {
      $fields = CustomField::where('module_name', 'register')
                             ->orderBy('position')
                             ->get();

        return view('superadmin.custom_fields.index', compact('fields'));
    }

    // Show the form to create a new custom field
    public function create()
    {
        return view('superadmin.custom_fields.create');
    }

    // Store new custom field
public function store(Request $request)
{
    $request->validate([
    'name' => 'required|string',
    'type' => 'required|string',
    'options' => 'nullable|string',
]);

CustomField::create([
    'name' => $request->name,
    'type' => $request->type,
    'required' => $request->has('required'),
      'module_name' => 'register',
    'options' => $request->options, // comma-separated
]);


    return redirect()->route('custom_fields.index')->with('success', 'Custom field added!');
}
public function update(Request $request, $id)
{
    $field = CustomField::findOrFail($id);

    $field->update([
        'name' => $request->name,
        'type' => $request->type,
        'required' => $request->has('required'),
        'options' => $request->options,
    ]);

    return redirect()->route('custom_fields.index')->with('success', 'Field updated successfully.');
}
public function destroy($id)
{
    $field = CustomField::findOrFail($id);
    $field->delete();

    return redirect()->route('custom_fields.index')->with('success', 'Field deleted successfully.');
}

   public function reorder(Request $request)
{
    foreach ($request->order as $item) {
        CustomField::where('id', $item['id'])
                   ->where('module_name', 'register')
                   ->update(['position' => $item['position']]);
    }

    return response()->json(['status' => 'success']);
}
}