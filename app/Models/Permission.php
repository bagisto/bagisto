<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'group', // products, orders, users, settings, etc.
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    // Scopes
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    // Methods
    public function assignToRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);
    }

    public function removeFromRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->detach($role);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permission) {
            if (empty($permission->slug)) {
                $permission->slug = \Illuminate\Support\Str::slug($permission->name);
            }
        });

        static::deleting(function ($permission) {
            if ($permission->is_system) {
                throw new \Exception('ไม่สามารถลบสิทธิ์ระบบได้');
            }
        });
    }
}
