<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $table = 'user_wallets';

    protected $primaryKey = 'id';

    public $timestamps = true; // Disable timestamps if `created_at` and `updated_at` are not present

    protected $fillable = [
        'u_code',
        'active_id',
        'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9', 'c10',
        'c11', 'c12', 'c13', 'c14', 'c15', 'c16', 'c17', 'c18', 'c19', 'c20',
        'c21', 'c22', 'c23', 'c24', 'c25', 'c26', 'c27', 'c28', 'c29', 'c30', 'c31',
        'active_at', 'created_at','updated_at',
    ];

    // Optional: Add a method to get wallet balance by column name
    public function getWalletBalance($column)
    {
        if (in_array($column, $this->getFillable())) {
            return $this->{$column};
        }

        return null;
    }

    // Optional: Set wallet balance by column
    public function setWalletBalance($column, $amount)
    {
        if (in_array($column, $this->getFillable())) {
            $this->{$column} = $amount;
            $this->save();
        }
    }
}