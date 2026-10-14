<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plan';

    protected $primaryKey = 'id';

    public $timestamps = false; // No created_at/updated_at columns

    protected $fillable = [
        'package_name',
        'rank',
        'level_income',
        'referral_income',
        'direct_income',
        'capping',
        'team_required',
        'left_team',
        'right_team',
        'reward_days',
        'reward',
        'status',
    ];

    protected $casts = [
        'level_income' => 'float',
        'referral_income' => 'float',
        'direct_income' => 'float',
        'left_team' => 'float',
        'right_team' => 'float',
        'capping' => 'integer',
        'team_required' => 'integer',
        'reward_days' => 'integer',
    ];
}
 