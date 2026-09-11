<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Contracts\AttributeOption as AttributeOptionContract;
use Webkul\Attribute\Database\Factories\AttributeOptionFactory;
use Webkul\Core\Eloquent\TranslatableModel;

class AttributeOption extends TranslatableModel implements AttributeOptionContract
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = ['label', 'swatch_alt'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_name',
        'swatch_value',
        'sort_order',
        'attribute_id',
    ];

    /**
     * The accessors to append to the model's array form.
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
     * Get the url of an image swatch's stored file, or null for a color or text swatch.
     */
    public function swatch_value_url()
    {
        if (
            $this->swatch_value
            && $this->attribute->swatch_type == 'image'
        ) {
            return Storage::url($this->swatch_value);
        }

        return null;
    }

    /**
     * Get the url of an image swatch's stored file.
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
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return AttributeOptionFactory::new();
    }
}
