<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FranchiseUser extends Authenticatable
{
    protected $table = 'franchise_users';

    protected $fillable = [
        'u_sponsor',
        'username',
        'name',
        'nominee_relation',
        'nominee_name',
        'franchise_name',
        'email',
        'mobile',
        'pan_no',
        'franchise_gst',
        'bank_name',
        'account_holder_name',
        'account_no',
        'ifsc_code',
        'bank_branch',
        'address',
        'country',
        'city',
        'state',
        'pin_code',
        'password',
        'active_status',
        'balance'
    ];

    protected $hidden = ['password'];
}
