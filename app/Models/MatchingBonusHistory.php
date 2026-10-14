<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchingBonusHistory extends Model
{
    use HasFactory;

    protected $table = 'matching_bonus_histories';

    protected $fillable = [
        'user_id',
        'bonus_amount',
        'matched_pairs',
        'matching_type',
        'matched_business',
        'left_flush',
        'right_flush',
        'date'
    ];

    // Disable timestamps if you're using date field instead
    public $timestamps = false;
    
    // Or enable them if you want created_at/updated_at
    // public $timestamps = true;

    protected $casts = [
        'date' => 'date',
        'bonus_amount' => 'decimal:2',
        'matched_pairs' => 'decimal:2',
        'left_flush' => 'decimal:2',
        'right_flush' => 'decimal:2',
        'matched_business' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}