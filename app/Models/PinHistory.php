<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinHistory extends Model
{
    protected $table = 'pin_history';

    protected $primaryKey = 'id';

    public $timestamps = false; // We're using custom timestamp fields

    protected $fillable = [
        'user_id',
        'tx_user',
        'credit',
        'debit',
        'pin_type',
        'tx_type',
        'remark',
        'prev_pin',
        'curr_pin',
        'retrieve_status',
        'updated_on',
        'added_on',
    ];

    protected $casts = [
        'tx_user' => 'integer',
        'credit' => 'integer',
        'debit' => 'integer',
        'retrieve_status' => 'integer',
        'updated_on' => 'datetime',
        'added_on' => 'datetime',
    ];
}
