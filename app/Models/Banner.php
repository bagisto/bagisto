<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'image',
        'mobile_image',
        'link_url',
        'link_text',
        'position', // home_slider, home_top, home_bottom, sidebar, category_top
        'sort_order',
        'start_date',
        'end_date',
        'is_active',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'click_count' => 'integer',
        'view_count' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function scopeByPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : null;
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->mobile_image 
            ? asset('storage/' . $this->mobile_image) 
            : $this->image_url;
    }

    // Methods
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    public function incrementView(): void
    {
        $this->increment('view_count');
    }

    public function incrementClick(): void
    {
        $this->increment('click_count');
    }

    public function getCtrAttribute(): float
    {
        if ($this->view_count === 0) {
            return 0;
        }

        return round(($this->click_count / $this->view_count) * 100, 2);
    }
}
