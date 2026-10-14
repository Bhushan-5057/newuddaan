<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model
{
    protected $table = 'user_payment_methods';

    protected $primaryKey = 'id';

    public $timestamps = false; // Since you're using `added_on` and `updated_on` instead of Laravel's default

    protected $fillable = [
        'u_code',
        'default_account',
        'accounts',
        'img',
        'status',
        'added_on',
        'updated_on',
    ];

    // Optionally cast status to boolean or integer
    protected $casts = [
        'status' => 'integer',
    ];

    // If you want to define a relation with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'u_code');
    }
}
