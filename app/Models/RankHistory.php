<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankHistory extends Model
{
    use HasFactory;

    protected $table='rank_rewards';

    protected $fillable=[

        'user_id',

        'rank',

        'slug',

        'rank_id',

        'reward',

        'bonus',

        'bonanza',

        'duration',

        'reward_status',

        'left_target',

        'right_target',

        'left_achieved',

        'right_achieved',

        'status',
    ];

    protected $casts=[

        'bonus'=>'decimal:2',

        'reward_status'=>'integer',

        'left_target'=>'integer',

        'right_target'=>'integer',

        'left_achieved'=>'integer',

        'right_achieved'=>'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}