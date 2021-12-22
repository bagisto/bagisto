<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Product\Database\Factories\ProductDownloadableLinkFactory;
use Webkul\Product\Contracts\ProductDownloadableLink as ProductDownloadableLinkContract;

class ProductDownloadableLink extends TranslatableModel implements ProductDownloadableLinkContract
{
    use HasFactory;

    public $translatedAttributes = ['title'];

    protected $fillable = [
        'title',
        'price',
        'url',
        'file',
        'file_name',
        'type',
        'sample_url',
        'sample_file',
        'sample_file_name',
        'sample_type',
        'sort_order',
        'product_id',
        'downloads',
    ];

    protected $with = ['translations'];

    /**
     * Get the product that owns the image.
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get image url for the file.
     */
    public function file_url(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the file.
     */
    public function getFileUrlAttribute(): string
    {
        return $this->file_url();
    }

    /**
     * Get image url for the sample file.
     */
    public function sample_file_url(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the sample file.
     */
    public function getSampleFileUrlAttribute(): string
    {
        return $this->sample_file_url();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        $translation = $this->translate(core()->getRequestedLocaleCode());

        $array['title'] = $translation->title ?? '';

        $array['file_url'] = $this->file ? Storage::url($this->file) : null;

        $array['sample_file_url'] = $this->sample_file ? Storage::url($this->sample_file) : null;

        return $array;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProductDownloadableLinkFactory::new();
    }
}