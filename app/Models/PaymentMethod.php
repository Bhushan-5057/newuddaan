<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_method';

    protected $primaryKey = 'id';

    public $timestamps = false; // Disable if you're using custom `added_on`

    protected $fillable = [
        'parent_method',
        'slug',
        'name',
        'image',
        'type',
        'address',
        'is_parent',
        'status',
        'added_on',
    ];

    // Optional: relationship for parent method
  public function parent()
    {
        return $this->belongsTo(PaymentMethod::class, 'parent_method', 'slug');
    }

    // Optional: relationship for child methods
    public function children()
    {
        return $this->hasMany(PaymentMethod::class, 'parent_method');
    }
}
