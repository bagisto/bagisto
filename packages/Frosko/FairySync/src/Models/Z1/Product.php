<?php

namespace Frosko\FairySync\Models\Z1;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed name
 * @property mixed tag
 * @property mixed meta_title
 * @property mixed meta_keyword
 * @property mixed seo_url
 * @property mixed color_id
 * @property mixed parent_product_id
 * @property mixed oc_product_id
 * @property ParentProduct $parentProduct
 * @property Collection|Image[] images
 */
class Product extends Model
{
    use HasFactory;

    protected $connection = 'z1';

    protected $fillable = [
        // Description fields
        'name',
        'tag',
        'meta_title',
        'meta_keyword',

        // Prices
        'price',
        'purchase_price',
        // Other
        'sku',
        'barcode',
        'seo_url',

        'color_id',
        'parent_product_id',
        'oc_product_id',
    ];

    protected $casts = [
        'name'         => 'json',
        'tag'          => 'json',
        'meta_title'   => 'json',
        'meta_keyword' => 'json',
        'seo_url'      => 'json',
    ];

    protected $touches = [
        'parentProduct',
    ];

    public function parentProduct(): BelongsTo
    {
        return $this->belongsTo(ParentProduct::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function getAttributeOrParent(string $attribute): string
    {
        return $this->getAttribute($attribute) ?: $this->parentProduct->getAttribute($attribute);
    }

    public function getTranslationOrParent(string $key, string $locale, bool $useFallbackLocale = true): mixed
    {
        return $this->getTranslation($key, $locale, false) ?: $this->parentProduct->getTranslation($key, $locale, $useFallbackLocale);
    }

    public function getDescriptionAttributes(array $languages): array
    {
        $descriptions = [];

        foreach ($languages as $languageId => $languageCode) {
            $descriptions[$languageId] = [
                'name'             => trim($this->getTranslationOrParent('name', $languageCode) ?? ''),
                'description'      => trim($this->parentProduct->getTranslation('description', $languageCode) ?? ''),
                'tag'              => trim($this->getTranslationOrParent('tag', $languageCode) ?? ''),
                'meta_title'       => trim($this->getTranslationOrParent('meta_title', $languageCode) ?? ''),
                'meta_description' => trim($this->parentProduct->getTranslation('meta_description', $languageCode) ?? ''),
                'meta_keyword'     => trim($this->getTranslationOrParent('meta_keyword', $languageCode) ?? ''),
            ];
        }

        return $descriptions;
    }
}
