<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin.payment_methods.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = PaymentMethod::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', fn ($row) => $row->status ? 'Active' : 'Inactive')
                ->editColumn('is_parent', fn ($row) => $row->is_parent ? 'Yes' : 'No')
                ->editColumn('image', fn ($row) => $row->image ? asset('storage/' . $row->image) : null)
                ->editColumn('added_on', fn ($row) => $row->added_on ? date('d-m-Y', strtotime($row->added_on)) : '')
               ->addColumn('actions', function ($row) {
    $buttons = '';

    if (auth()->user()->hasPermission('Payment Methods', 'can_edit')) {
        $buttons .= '<button class="btn btn-sm btn-info edit-payment-method me-1" data-id="' . $row->id . '">Edit</button>';
    }

    if (auth()->user()->hasPermission('Payment Methods', 'can_delete')) {
        $buttons .= '<button class="btn btn-sm btn-danger delete-payment-method mt-2" data-id="' . $row->id . '">Delete</button>';
    }

    return $buttons;
})
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|unique:payment_method,slug',
            'type' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|boolean',
       
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only([
            'slug', 'name', 'type', 'address', 'status', 'added_on'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('payment_methods', 'public');
        }

        if ($request->filled('parent_method')) {
            $data['parent_method'] = $request->parent_method;
            $data['is_parent'] = 0;
        } else {
            $data['parent_method'] = null;
            $data['is_parent'] = 1;
        }

        PaymentMethod::create($data);

        return redirect()->route('superadmin.paymentmethods.index')
                         ->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->image_url = $method->image ? asset('storage/' . $method->image) : null;
        return response()->json($method);
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|unique:payment_method,slug,' . $id,
            'type' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|boolean',
           
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only([
            'slug', 'name', 'type', 'address', 'status', 'added_on'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($method->image && Storage::disk('public')->exists($method->image)) {
                Storage::disk('public')->delete($method->image);
            }
            $data['image'] = $request->file('image')->store('payment_methods', 'public');
        }

        if ($request->filled('parent_method')) {
            $data['parent_method'] = $request->parent_method;
            $data['is_parent'] = 0;
        } else {
            $data['parent_method'] = null;
            $data['is_parent'] = 1;
        }

        $method->update($data);

        return redirect()->route('admin.paymentmethods.index')
                         ->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // Delete image if exists
        if ($method->image && Storage::disk('public')->exists($method->image)) {
            Storage::disk('public')->delete($method->image);
        }

        $method->delete();

        return response()->json(['message' => 'Payment method deleted successfully']);
    }
}
