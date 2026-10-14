<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BoosterIncome extends Model

{

    use HasFactory;



    protected $fillable = [

        'user_id',

        'from_package_id',

        'amount',
        'note',

    ];







}