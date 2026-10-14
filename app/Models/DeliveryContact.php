<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryContact extends Model
{
    use HasFactory;

    // ટેબલનું નામ
    protected $table = 'delivery_contacts';

    const CREATED_AT = 'added_on';
    const UPDATED_AT = 'updated_on';

protected $fillable = ['u_code', 'title', 'address', 'contact_no', 'is_default','city','state','country','pincode','email'];

    /**
     * યુઝર સાથેનો સંબંધ (Belongs To User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'u_code', 'id');
    }

    /**
     * સ્કોપ: માત્ર લોગિન થયેલા યુઝરના એડ્રેસ મેળવવા માટે
     */
    public function scopeCurrentUser($query)
    {
        return $query->where('u_code', auth()->id());
    }

    /**
     * સ્કોપ: ડિફોલ્ટ એડ્રેસ મેળવવા માટે
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }
}


