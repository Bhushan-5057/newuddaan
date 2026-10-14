<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonanza extends Model
{
    protected $fillable = [
        'trip_name',
        'self_id',
        'direct',
        'matching_business',
        'start_date',
        'end_date',
    ];
}
