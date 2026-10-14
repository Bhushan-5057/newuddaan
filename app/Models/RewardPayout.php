<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class RewardPayout extends Model
{
    use HasFactory;

  
    protected $fillable = [
        'user_id',
        'rank',
        'amount',
        'week_start',
        'week_end',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
    ];
}