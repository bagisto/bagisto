<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Attribute\Contracts\AttributeOption as AttributeOptionContract;
use Webkul\Attribute\Database\Factories\AttributeOptionFactory;
use Webkul\Core\Eloquent\TranslatableModel;

class AttributeOption extends TranslatableModel implements AttributeOptionContract
{
    use HasFactory;

    public $timestamps = false;

    public $translatedAttributes = ['label', 'swatch_alt'];

    protected $fillable = [
        'admin_name',
        'swatch_value',
        'sort_order',
        'attribute_id',
    ];

    /**
     * Append to the model attributes
     *
     * @var array
     */
    protected $appends = [
        'swatch_value_url',
        'swatch_file_name',
    ];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(AttributeProxy::modelClass());
    }

    /**
     * Get image url for the swatch value url.
     */
    public function swatch_value_url()
    {
        if (
            $this->swatch_value
            && $this->attribute->swatch_type == 'image'
        ) {
            return url('cache/small/'.$this->swatch_value);
        }

        return null;
    }

    /**
     * Get image url for the product image.
     */
    public function getSwatchValueUrlAttribute()
    {
        return $this->swatch_value_url();
    }

    /**
     * Get the swatch file name, without the directory and the extension. Empty for
     * color and text swatches, which hold a plain value rather than a path.
     */
    public function getSwatchFileNameAttribute(): string
    {
        if ($this->attribute?->swatch_type !== 'image') {
            return '';
        }

        return pathinfo((string) $this->swatch_value, PATHINFO_FILENAME);
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return AttributeOptionFactory::new();
    }
}
