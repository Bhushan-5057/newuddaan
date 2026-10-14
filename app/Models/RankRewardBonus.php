<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- અહીં 'Eloquent' ઉમેરીને સુધારો કર્યો છે

class RankRewardBonus extends Model
{
    use HasFactory;

    protected $table = 'rank_reward_bonuses';

    // માસ અસાઇનમેન્ટ (Mass Assignment) માટે મંજૂર કરેલા ફીલ્ડ્સ
    protected $fillable = [
        'name',
        'slug',
        'left',
        'right',
        'bonus',
        'reward',
        'bonanza',
        'duration'
    ];

    // જો બોનસ અમાઉન્ટને ફ્લોટ/ડેસિમલમાં રાખવી હોય તો ડેટા કાસ્ટિંગ
    protected $casts = [
        'left' => 'integer',
        'right' => 'integer',
        'bonus' => 'float',
    ];
}