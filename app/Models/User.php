<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'user_type', // customer, vendor, admin
        'status', // active, inactive, suspended
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'last_login_ip',
        'preferred_language',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewed::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function storeCredits()
    {
        return $this->hasMany(StoreCredit::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function mlmCommissions()
    {
        return $this->hasMany(MlmCommission::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCustomers($query)
    {
        return $query->where('user_type', 'customer');
    }

    public function scopeVendors($query)
    {
        return $query->where('user_type', 'vendor');
    }

    public function scopeAdmins($query)
    {
        return $query->where('user_type', 'admin');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : asset('images/default-avatar.png');
    }

    // Methods
    public function isVendor(): bool
    {
        return $this->user_type === 'vendor';
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function getTotalStoreCreditAttribute()
    {
        return $this->storeCredits()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->sum('balance');
    }
}
