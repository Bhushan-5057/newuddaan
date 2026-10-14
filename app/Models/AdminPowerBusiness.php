<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPowerBusiness extends Model
{
    protected $table = 'admin_power_businesses';

    protected $fillable = [
        'user_id',
        'matching_type',
        'position',
        'bv_amount',
        'remark'
    ];

    // યુઝર સાથેનો સંબંધ (Relationship)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}