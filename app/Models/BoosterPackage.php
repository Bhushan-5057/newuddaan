<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BoosterPackage extends Model

{

    use HasFactory;



 

    protected $fillable = [

        'user_id',

        'placed_under_id',

        'position',

        'status',

    ];

// App\Models\BoosterPackage.php

public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}

public function children()
{
    return $this->hasMany(BoosterPackage::class, 'placed_under_id', 'id');
}









 

}