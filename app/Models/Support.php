<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Support extends Model
{
    use HasFactory;

    protected $table = 'support';

    protected $primaryKey = 'id';

    public $timestamps = false; // since you're manually using `timestamp` and `updated_on`

    protected $fillable = [
        'u_code',
        'message',
        'first_name',
        'last_name',
        'email',
        'contactno',
        'subject',
        'ticket',
        'time',
        'msg_by',
        'status',
        'reply_status',
        'approved_date',
        'timestamp',
        'updated_on',
        'reply',
    ];

    protected $casts = [
     
        'reply_status' => 'boolean',
        'time' => 'datetime',
        'approved_date' => 'date',
        'timestamp' => 'datetime',
        'updated_on' => 'datetime',
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'u_code', 'id'); // change 'id' if needed
}
}
