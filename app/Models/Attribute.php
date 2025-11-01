<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'type', // select, radio, checkbox, text, color, image
        'is_required',
        'is_filterable',
        'is_visible',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Methods
    public function addValue(array $data): AttributeValue
    {
        return $this->values()->create($data);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = \Illuminate\Support\Str::slug($attribute->name);
            }
        });
    }
}
