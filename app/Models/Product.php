<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id','sub_category_id',
        'p_code','slug','name',
        'p_dese','other_dese',
        'imgs','more_imgs',
        'mrp','dp','gst_percent','hsn_code',
        'qty','product_bv','pv','status','weight','length','breadth','height'
    ];

    protected $casts = [
        'more_imgs' => 'array',   // 🔥 MOST IMPORTANT
    ];

    public function getDiscountPercentAttribute()
    {
        if ($this->mrp > 0) {
            return round((($this->mrp - $this->dp) / $this->mrp) * 100);
        }
        return 0;
    }

    // 🔥 ADD THESE ACCESSORS FOR FORMATTED DESCRIPTION
    public function getFormattedPDescAttribute()
    {
        // Convert newlines to <br> tags and escape HTML
        return nl2br(e($this->p_dese));
    }

    public function getFormattedOtherDescAttribute()
    {
        // Convert newlines to <br> tags and escape HTML
        return nl2br(e($this->other_dese));
    }

    // 🔥 IF YOU WANT TO ALLOW HTML (for WYSIWYG editor), use this instead:
    // public function getFormattedPDescAttribute()
    // {
    //     return $this->p_dese; // Direct HTML output (use with caution)
    // }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }
}