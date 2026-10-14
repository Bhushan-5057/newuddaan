<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $table = 'user_permissions';

    protected $fillable = [
        'user_id',
        'permission_slug',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
    ];

    protected $casts = [
        'can_view'   => 'boolean',
        'can_add'    => 'boolean',
        'can_edit'   => 'boolean',
        'can_delete' => 'boolean',
    ];

    /**
     * See RolePermission: keeps User::hasPermission()'s per-request cache
     * honest after a write.
     */
    protected static function booted(): void
    {
        static::saved(fn () => User::flushPermissionCache());
        static::deleted(fn () => User::flushPermissionCache());
    }

    /**
     * Relation: permission belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
