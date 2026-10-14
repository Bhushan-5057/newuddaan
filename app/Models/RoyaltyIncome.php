<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoyaltyIncome extends Model
{
    use HasFactory;

    protected $table = 'royalty_incomes';

    protected $fillable = [
        'user_id',
        'rank',
        'required_business',
        'daily_amount',
        'reward_amount',
        'start_date',
        'end_date',
        'days_paid',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'days_paid'  => 'integer',
    ];

    /**
     * Relationship → User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope → Active Royalty
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if royalty is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
