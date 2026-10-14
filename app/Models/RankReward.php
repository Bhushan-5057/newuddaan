<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class RankReward extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'rank',
        'rank_id',
        'reward',
        'status',
    ];

 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}