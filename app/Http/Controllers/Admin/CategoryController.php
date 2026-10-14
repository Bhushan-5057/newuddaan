<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // List
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    // Store Method
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:1024',
            'icon' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:512'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['bogo_status'] = $request->has('bogo_status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories/images', 'public');
        }
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('categories/icons', 'public');
        }

        Category::create($data);
        return back()->with('success', 'Category Added Successfully');
    }

    // Update Method
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $category->image = $request->file('image')->store('categories/images', 'public');
        }
        
        if ($request->hasFile('icon')) {
            if ($category->icon) Storage::disk('public')->delete($category->icon);
            $category->icon = $request->file('icon')->store('categories/icons', 'public');
        }

        $category->update([
            'name' => $request->name,
            'has_subcategory' => $request->has_subcategory,
            'allow_direct_product' => $request->allow_direct_product,
            'bogo_status' => $request->has('bogo_status') ? 1 : 0,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Category Updated');
    }

    // Toggle BOGO Status
    public function toggleBogo($id)
    {
        $category = Category::findOrFail($id);
        $category->bogo_status = !$category->bogo_status;
        $category->save();

        return response()->json([
            'success' => true,
            'bogo_status' => $category->bogo_status,
            'message' => 'BOGO status updated successfully'
        ]);
    }

    // Destroy Method
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $category = Category::with(['subCategories.products', 'products'])
                ->findOrFail($id);

            $deleteProduct = function ($product) {
                if ($product->imgs && Storage::disk('public')->exists($product->imgs)) {
                    Storage::disk('public')->delete($product->imgs);
                }

                if ($product->more_imgs && is_array($product->more_imgs)) {
                    foreach ($product->more_imgs as $img) {
                        if (Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                }
                $product->delete();
            };

            foreach ($category->products as $product) {
                $deleteProduct($product);
            }

            foreach ($category->subCategories as $sub) {
                foreach ($sub->products as $product) {
                    $deleteProduct($product);
                }
                $sub->delete();
            }

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $category->delete();
        });

        return back()->with('success', 'Category, sub categories, products and all images deleted successfully.');
    }
}