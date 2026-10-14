<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $products = Product::with('category', 'subCategory')->latest()->get();

        return view('admin.product.index', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $slug = \Illuminate\Support\Str::slug($request->name);
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'p_code' => 'required|unique:products,p_code',
            'name' => 'required|string|max:255',
            'mrp' => 'required|numeric',
            'dp' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'product_bv' => 'required|numeric',
            'pv' => 'required|numeric',
            'gst_percent' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'breadth' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'imgs' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'more_imgs' => 'nullable|array|max:4',
            'more_imgs.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $exists = Product::where('slug', $slug)->exists();
        if ($exists) {
            return back()
                ->withErrors(['name' => 'Product name already exists, please use a unique name.'])
                ->withInput();
        }

        if ($request->sub_category_id) {
            $valid = SubCategory::where('id', $request->sub_category_id)
                ->where('category_id', $request->category_id)
                ->exists();

            if (!$valid) {
                return back()
                    ->withErrors(['sub_category_id' => 'Invalid sub category selected'])
                    ->withInput();
            }
        }

        // MAIN IMAGE
        $mainImg = $request->file('imgs')->store('products', 'public');

        $gallery = [];
        if ($request->hasFile('more_imgs')) {
            foreach ($request->file('more_imgs') as $img) {
                $gallery[] = $img->store('products/gallery', 'public');
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'p_code' => $request->p_code,
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'p_dese' => $request->p_dese,
            'other_dese' => $request->other_dese,
            'imgs' => $mainImg,
            'more_imgs' => $gallery,
            'mrp' => $request->mrp,
            'dp' => $request->dp,
            'gst_percent' => $request->gst_percent,
            'hsn_code' => $request->hsn_code,
            'qty' => $request->qty,
            'product_bv' => $request->product_bv,
            'pv' => $request->pv,
            'weight' => $request->weight,
            'length' => $request->length,
            'breadth' => $request->breadth,
            'height' => $request->height,
            'status' => 1
        ]);

        return redirect()->route('admin.product.list')->with('success', 'Product Added Successfully');
    }

    public function list(Request $request)
    {
        $query = Product::with(['category']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('p_code', 'like', "%{$request->keyword}%");
            });
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('status', 1)->get();

        return view('admin.product.list', compact('products', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'mrp' => 'required|numeric',
            'dp' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'product_bv' => 'required|numeric',
            'pv' => 'required|numeric',
            'gst_percent' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'breadth' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
        ]);

        // MAIN IMAGE Update Logic
        if ($request->hasFile('imgs')) {
            if ($product->imgs && Storage::disk('public')->exists($product->imgs)) {
                Storage::disk('public')->delete($product->imgs);
            }
            $product->imgs = $request->file('imgs')->store('products', 'public');
        }

        // GALLERY IMAGES Update Logic
        if ($request->hasFile('more_imgs')) {
            if ($product->more_imgs && is_array($product->more_imgs)) {
                foreach ($product->more_imgs as $old_img) {
                    if (Storage::disk('public')->exists($old_img)) {
                        Storage::disk('public')->delete($old_img);
                    }
                }
            }

            $gallery = [];
            foreach ($request->file('more_imgs') as $img) {
                $gallery[] = $img->store('products/gallery', 'public');
            }
            $product->more_imgs = $gallery;
        }

        // Update other fields including dimensions and descriptions
        $product->update([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'name' => $request->name,
            'mrp' => $request->mrp,
            'dp' => $request->dp,
            'gst_percent' => $request->gst_percent,
            'hsn_code' => $request->hsn_code,
            'qty' => $request->qty,
            'product_bv' => $request->product_bv,
            'pv' => $request->pv,
            'weight' => $request->weight,
            'length' => $request->length,
            'breadth' => $request->breadth,
            'height' => $request->height,
            'p_dese' => $request->p_dese,
            'other_dese' => $request->other_dese,
        ]);

        return redirect()->route('admin.product.list')->with('success', 'Product Updated Successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Main image
        if ($product->imgs && Storage::disk('public')->exists($product->imgs)) {
            Storage::disk('public')->delete($product->imgs);
        }

        // Gallery images
        if ($product->more_imgs && is_array($product->more_imgs)) {
            foreach ($product->more_imgs as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.product.list')->with('success', 'Product and all images deleted successfully');
    }

    public function productstock(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('p_code', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->stock === 'low') {
            $query->whereColumn('qty', '<=', 'low_stock_alert')
                ->where('qty', '>', 0);
        }

        if ($request->stock === 'out') {
            $query->where('qty', 0);
        }

        $products = $query->orderBy('qty')->paginate(15);
        $categories = Category::where('status', 1)->get();

        return view('admin.product.stock', compact('products', 'categories'));
    }
}