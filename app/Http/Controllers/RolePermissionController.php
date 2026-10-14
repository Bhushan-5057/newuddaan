<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    // Show permission page

public function index()
{
    $defaultRole = 'admin';
    return view('superadmin.permissions.index', compact('defaultRole'));
}

public function data(Request $request)
{
    $role = $request->input('role', 'admin');

    $permissions = RolePermission::where('role', $role)->get();

    $data = $permissions->map(function ($perm) {
        $row = [
            'id' => $perm->id,
            'feature' => $perm->feature,
            'role' => $perm->role,
            'can_view_checkbox' => $perm->can_view,
            'can_create_checkbox' => $perm->can_create,
            'can_edit_checkbox' => $perm->can_edit,
            'can_delete_checkbox' => $perm->can_delete,
        ];

        // Decode options JSON if stored that way
        $options = json_decode($perm->options ?? '{}', true);

        foreach (['can_view', 'can_create', 'can_edit', 'can_delete'] as $key) {
            $row[$key] = isset($options[$key]) && $options[$key] ? true : false;
        }

        return $row;
    });

    return response()->json(['data' => $data]);
}

    // Store new permission
public function store(Request $request)
{
    $data = $request->validate([
        'feature' => 'required|string|max:255',
        'role' => 'required|string|in:admin,user',
    ]);

    // Prevent duplicate
    $exists = RolePermission::where('feature', $data['feature'])
        ->where('role', $data['role'])
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors(['feature' => 'This permission already exists for the selected role.']);
    }

    // Create options array
    $options = [
        'can_view' => $request->has('can_view'),
        'can_create' => $request->has('can_create'),
        'can_edit' => $request->has('can_edit'),
        'can_delete' => $request->has('can_delete'),
    ];

    // Now JSON encode it before saving
    RolePermission::create([
        'feature' => $data['feature'],
        'role' => $data['role'],
        'options' => json_encode($options),
    ]);

    return redirect()->back()->with('success', 'Permission added successfully.');
}



    // Ajax update from toggle
public function update(Request $request)
{
    $request->validate([
        'role' => 'required|string|in:admin,user',
        'feature' => 'required|string|max:255',
        'permission' => 'required|in:can_view,can_create,can_edit,can_delete',
        'value' => 'required|boolean',
    ]);

    $permission = RolePermission::firstOrCreate(
        [
            'role' => $request->role,
            'feature' => $request->feature,
        ],
        [
            'can_view' => 0,
            'can_create' => 0,
            'can_edit' => 0,
            'can_delete' => 0,
        ]
    );

    $permission->{$request->permission} = $request->value;
    $permission->save();

    return response()->json(['message' => 'Permission updated successfully.']);
}



public function updatePermission(Request $request, $id)
{
    $data = $request->validate([
        'feature' => 'required|string|max:255',
        'role' => 'required|string|in:admin,user',
    ]);

    $permission = RolePermission::findOrFail($id);

    // Prevent duplicate for same feature+role (excluding current)
    $exists = RolePermission::where('feature', $data['feature'])
        ->where('role', $data['role'])
        ->where('id', '!=', $id)
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors(['feature' => 'This permission already exists for the selected role.']);
    }

    // Rebuild the options array from checkboxes
    $options = [
        'can_view'   => $request->has('can_view'),
        'can_create' => $request->has('can_create'),
        'can_edit'   => $request->has('can_edit'),
        'can_delete' => $request->has('can_delete'),
    ];

    // Save changes
    $permission->update([
        'feature' => $data['feature'],
        'role'    => $data['role'],
        'options' => json_encode($options),
    ]);

    return redirect()->back()->with('success', 'Permission updated successfully.');
}
public function destroy($id)
{
    $permission = RolePermission::findOrFail($id);
    $permission->delete();

    return response()->json(['success' => true, 'message' => 'Permission deleted successfully.']);
}


}
