<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? asset('storage/' . $this->image_path) 
            : asset('images/no-image.png');
    }

    // Methods
    public function setAsPrimary(): void
    {
        // Remove primary from other images
        static::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            // Set as primary if it's the first image
            if (!static::where('product_id', $image->product_id)->exists()) {
                $image->is_primary = true;
            }

            // Set sort order
            if (is_null($image->sort_order)) {
                $maxOrder = static::where('product_id', $image->product_id)->max('sort_order') ?? 0;
                $image->sort_order = $maxOrder + 1;
            }
        });
    }
}
