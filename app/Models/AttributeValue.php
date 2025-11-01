<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'value_en',
        'color_code', // For color type attributes
        'image_path', // For image type attributes
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Relationships
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
            : null;
    }

    public function getDisplayValueAttribute()
    {
        if ($this->attribute->type === 'color' && $this->color_code) {
            return "<span style='background-color: {$this->color_code}; width: 20px; height: 20px; display: inline-block; border: 1px solid #ccc;'></span> {$this->value}";
        }

        return $this->value;
    }
}
