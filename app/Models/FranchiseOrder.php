<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseOrder extends Model
{
    protected $table = 'franchise_orders';
    protected $guarded = [];

    public function franchise()
    {
        return $this->belongsTo(FranchiseUser::class, 'franchise_id');
    }

    public function items()
    {
        return $this->hasMany(FranchiseOrderItem::class, 'order_id');
    }
}