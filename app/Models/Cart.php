<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'u_code',
        'product_id',
        'quantity',
        'dp',
        'product_bv',
        'pv'
    ];

    // પ્રોડક્ટ સાથેનું રિલેશન
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}