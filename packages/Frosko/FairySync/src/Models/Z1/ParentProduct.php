<?php

namespace Frosko\FairySync\Models\Z1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentProduct extends Model
{
    use HasFactory;

    protected $connection = 'z1';

    protected $fillable = [
        'sku',
        'weight',
        'base_category',

        'material_id',
        'manufacturer_id',
        'origin_id',
        // Description fields.
        'name',
        'description',
        'tag',
        'meta_title',
        'meta_description',
        'meta_keyword',

        'price',
        'purchase_price',
    ];

    public $casts = [
        'name'             => 'json',
        'description'      => 'json',
        'tag'              => 'json',
        'meta_title'       => 'json',
        'meta_description' => 'json',
        'meta_keyword'     => 'json',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'parent_product_category'
        );
    }
}
