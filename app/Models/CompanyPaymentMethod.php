<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPaymentMethod extends Model
{
    protected $table = 'company_payment_methods';

    protected $primaryKey = 'id';

    public $timestamps = false; // Because you use `added_on` and `updated_on` manually

    protected $fillable = [
        'method_name',
        'unique_name',
        'default_account',
        'company_accounts',
        'fields_required',
        'image',
        'type',
        'status',
        'added_on',
        'updated_on',
    ];

    protected $casts = [
        'fields_required' => 'array',
        'default_account' => 'boolean',
        'status' => 'boolean',
    ];

    protected $dates = [
        'added_on',
        'updated_on',
    ];
}
