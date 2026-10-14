<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    protected $fillable = [
        'order_id',
        'tx_u_code',
        'u_code',
        'tx_type',
        'debit_credit',
        'source',
        'wallet_type',
        'ftm_amnt',
        'amount',
        'tx_charge',
        'tx_hash',
        'payment_type',
        'payment_slip',
        'date',
        'status',
        'remark',
        'open_wallet',
        'closing_wallet',
        'request_amount',
        'paidAmount',
        'bank_details',
        'is_seen',
        'added_on',
        'updated_on',
        'account_details'
    ];
public function user()
{
    return $this->belongsTo(User::class, 'u_code', 'id');
}

    public $timestamps = false; // Since you're manually using added_on/updated_on
}