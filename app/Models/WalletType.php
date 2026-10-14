<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    protected $table = 'wallet_types';

    protected $fillable = [
        'name',
        'slug',
        'count_in_wallet',
        'wallet_type',
        'status',
        'wallet_column',
        'binary',
        'single_leg',
        'generation',
        'product_base',
        'universal',
        'investment',
        'repurchase',
        'ecommerce',
        'user_route'
    ];

    public $timestamps = false;

    const CREATED_AT = 'added_on';
    const UPDATED_AT = 'updated_on';
}