<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    protected $table = 'notice_board';

    // If your primary key is 'id' and it's auto-increment, no need to define it explicitly.
    protected $primaryKey = 'id';

    // Disable timestamps if you're not using Laravel's default `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable fields for mass assignment
    protected $fillable = [
        'title',
        'description',
        'img_path',
        'type',
        'status',
        'added_on',
        'updated_on'
    ];

    // If you want to use Carbon for date casting
    protected $dates = [
        'added_on',
        'updated_on',
    ];
}
