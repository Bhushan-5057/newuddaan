<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiningBoosterHistory extends Model
{
    use HasFactory;

    protected $table = 'mining_booster_histories';

   
    protected $fillable = [
        'user_id',
        'booster_level',
        'bonus_amount',
    ];

   
}