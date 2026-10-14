<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceInfo extends Model
{
    protected $table = 'advanced_info';

   // app/Models/AdvanceInfo.php
protected $fillable = [
    'title',
    'name',
    'label',
    'value',
    'type',
    'options',
    'status',
    'admin_status',
];

    public $timestamps = false; // Since added_on is used instead of created_at
}