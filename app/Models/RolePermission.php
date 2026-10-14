<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    class RolePermission extends Model
    {
    protected $fillable = [
    'role', 'feature','options', 'can_view', 'can_create', 'can_edit', 'can_delete'
    ];

    /**
     * User::hasPermission() caches these rows for the life of the request, so
     * any write has to invalidate that cache or the same request would keep
     * answering from stale values.
     */
    protected static function booted(): void
    {
        static::saved(fn () => User::flushPermissionCache());
        static::deleted(fn () => User::flushPermissionCache());
    }
    public function role()
    {
    return $this->belongsTo(RolePermission::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    }