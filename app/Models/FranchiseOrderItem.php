<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseOrderItem extends Model
{
    protected $table = 'franchise_order_items';

    protected $fillable = [
        'franchise_id',
        'order_id',
        'product_id',
        'name',
        'quantity',          // ✅ renamed from quantity
        'product_bv',           // ✅ renamed from product_bv
        'sub_total',
        'sub_mrp',
        'status',
        'approved_at',
        'rejected_at'
    ];

    /* ================= RELATIONS ================= */

    public function order()
    {
        return $this->belongsTo(FranchiseOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function franchise()
    {
        return $this->belongsTo(FranchiseUser::class, 'franchise_id');
    }
}