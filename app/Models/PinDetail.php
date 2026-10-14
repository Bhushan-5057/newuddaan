<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinDetail extends Model
{
    protected $table = 'pin_details';

    protected $primaryKey = 'id';

    public $timestamps = false; // We're using custom timestamp fields

    protected $fillable = [
        'pin_type',
        'package_name',
        'vip',
        'pin_rate',
        'pin_rate2',
        'roi',
        'business_volumn',
        'pin_value',
        'status',
        'added_on',
        'updated_on',
        'custom_fields',
    ];

    protected $casts = [
        'pin_rate2' => 'float',
        'roi' => 'float',
        'business_volumn' => 'float',
        'pin_value' => 'float',
        'vip' => 'integer',
        'status' => 'integer',
        'added_on' => 'datetime',
        'updated_on' => 'datetime',
    ];
}
