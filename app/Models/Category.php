<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name','slug','image','icon','has_subcategory','allow_direct_product','status', 'bogo_status' // Add bogo_status
    ];

    public function subCategories(){
        return $this->hasMany(SubCategory::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}