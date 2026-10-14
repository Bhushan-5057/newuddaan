<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    // Set the table name
    protected $table = 'company_info';

    // Disable default timestamps (created_at, updated_at)
    public $timestamps = false;

    // Fillable fields for mass assignment
    protected $fillable = [
        'title',
        'label',
        'value',
        'type',
        'status',
        'admin_status',
        'added_on',
    ];

    // Casts (optional, helps with proper data handling)
    protected $casts = [
    
        'added_on' => 'datetime',
    ];
}