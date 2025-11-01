<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status', // subscribed, unsubscribed, bounced
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_reason',
        'verification_token',
        'verified_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Scopes
    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    // Methods
    public function subscribe(): void
    {
        $this->update([
            'status' => 'subscribed',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'unsubscribe_reason' => null,
        ]);
    }

    public function unsubscribe(?string $reason = null): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
            'unsubscribe_reason' => $reason,
        ]);
    }

    public function verify(): void
    {
        $this->update([
            'verified_at' => now(),
            'verification_token' => null,
        ]);
    }

    public function generateVerificationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        
        $this->update(['verification_token' => $token]);
        
        return $token;
    }

    public function isSubscribed(): bool
    {
        return $this->status === 'subscribed';
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            $subscriber->ip_address = request()->ip();
            $subscriber->user_agent = request()->userAgent();
            
            if (empty($subscriber->verification_token)) {
                $subscriber->verification_token = bin2hex(random_bytes(32));
            }
        });
    }
}
