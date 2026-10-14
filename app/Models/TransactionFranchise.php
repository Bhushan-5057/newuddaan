<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFranchise extends Model
{
    protected $table = 'transaction_franchise';

    protected $fillable = [
        'u_code',
        'tx_type',
        'debit_credit',
        'wallet_type',
        'amount',
        'img',
        'tx_record',
        'open_wallet',
        'closing_wallet',
        'remark',
        'status',
        'reject_reason',
        'approved_at',
        'rejected_at'
    ];

    public $timestamps = true;

    // ✅ ADD THIS RELATIONSHIP
    public function franchise()
    {
        return $this->belongsTo(
            FranchiseUser::class,   // Model
            'u_code',               // Foreign key in transaction_franchise
            'username'              // Primary/unique key in franchise_users
        );
    }
}