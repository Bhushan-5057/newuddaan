<?php

// app/Http/Controllers/Admin/SubCategoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class SubCategoryController extends Controller
{

    public function index()
{
    $categories = Category::where('status',1)
        ->where('has_subcategory',1) // 🔥 IMPORTANT
        ->get();

    $subCategories = SubCategory::with('category')->latest()->get();

    return view('admin.subcategory.index', compact('categories','subCategories'));
}


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255'
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name), // fixed slug
            'status'      => 1
        ]);

        return back()->with('success', 'Sub Category Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255'
        ]);

        $sub = SubCategory::findOrFail($id);

        $sub->update([
            'category_id' => $request->category_id,
            'name'        => $request->name
            // slug NOT changed
        ]);

        return back()->with('success', 'Sub Category Updated Successfully');
    }



public function destroy($id)
{
    $subCategory = SubCategory::with('products')->findOrFail($id);

    // 🔥 Delete all products under this subcategory
    foreach ($subCategory->products as $product) {

        // Main image delete
        if ($product->imgs && Storage::disk('public')->exists($product->imgs)) {
            Storage::disk('public')->delete($product->imgs);
        }

        // Gallery images delete
        if ($product->more_imgs && is_array($product->more_imgs)) {
            foreach ($product->more_imgs as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        $product->delete();
    }

    // 🔥 Finally delete subcategory
    $subCategory->delete();

    return back()->with('success', 'Sub category and all related products deleted successfully');
}


}
