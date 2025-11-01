<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'logo',
        'banner',
        'website',
        'is_featured',
        'sort_order',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/default-brand.png');
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner 
            ? asset('storage/' . $this->banner) 
            : null;
    }

    public function getUrlAttribute()
    {
        return route('brands.show', $this->slug);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = \Illuminate\Support\Str::slug($brand->name);
            }
        });
    }
}
