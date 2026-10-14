<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RewardTracking extends Model
{
    use HasFactory;
    protected $table = 'royalty_rank';

    protected $fillable = [
        'u_code',
        'rank_id',
        'rank',
        'royalty',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $casts = [

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'u_code', 'id');
    }
}
