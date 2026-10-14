<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'custom_field_ids', 'value','module_name'];
    
    protected $casts = [
    'custom_field_ids' => 'array',
    'value' => 'array',
];

}