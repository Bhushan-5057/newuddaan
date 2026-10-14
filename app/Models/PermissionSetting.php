<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/PermissionSetting.php
class PermissionSetting extends Model
{
    protected $fillable = ['admin_id', 'feature', 'can_view','can_create', 'can_edit', 'can_delete'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}