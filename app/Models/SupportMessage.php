<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $table = 'support_messages';

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'message',
    ];

    public $timestamps = false; // because your table only uses `created_at`, not `updated_at`

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    // Optional: if you want to retrieve sender info
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // if you plan to store sender_id too
    }
}
