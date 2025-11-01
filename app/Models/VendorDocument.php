<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'document_type', // id_card, business_license, tax_certificate, bank_statement
        'document_number',
        'file_path',
        'status', // pending, approved, rejected
        'verified_at',
        'verified_by',
        'rejection_reason',
        'expires_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return $this->file_path 
            ? asset('storage/' . $this->file_path) 
            : null;
    }

    public function getDocumentTypeNameAttribute()
    {
        $types = [
            'id_card' => 'บัตรประชาชน',
            'business_license' => 'ใบอนุญาตประกอบธุรกิจ',
            'tax_certificate' => 'ทะเบียนภาษี',
            'bank_statement' => 'หนังสือรับรองบัญชีธนาคาร',
        ];

        return $types[$this->document_type] ?? $this->document_type;
    }

    // Methods
    public function approve(int $verifiedBy): void
    {
        $this->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason, int $verifiedBy): void
    {
        $this->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
            'rejection_reason' => $reason,
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expires_at 
            && $this->expires_at->isFuture() 
            && $this->expires_at->diffInDays(now()) <= $days;
    }
}
