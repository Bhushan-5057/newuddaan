<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonanzaAchiever extends Model
{
    protected $fillable = [
        'bonanza_id',
        'user_id',
        'direct_count',
        'direct_business',
        'left_business',
        'right_business',
        'matching_business',
        'self_business',
        'qualified_at',
        'status',
    ];

    protected $casts = [
        'qualified_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

  public function bonanza()
{
    return $this->belongsTo(Bonanza::class, 'bonanza_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
