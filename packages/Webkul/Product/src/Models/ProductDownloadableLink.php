<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductDownloadableLink as ProductDownloadableLinkContract;

class ProductDownloadableLink extends TranslatableModel implements ProductDownloadableLinkContract
{
    public $translatedAttributes = ['title'];

    protected $fillable = ['title', 'url', 'file', 'file_name', 'type', 'sample_url', 'sample_file', 'sample_file_name', 'sample_type', 'sort_order', 'product_id', 'downloads'];

    protected $with = ['translations'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get image url for the file.
     */
    public function file_url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the file.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_url();
    }

    /**
     * Get image url for the sample file.
     */
    public function sample_file_url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the sample file.
     */
    public function getSampleFileUrlAttribute()
    {
        return $this->sample_file_url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $translation = $this->translate(request()->get('locale') ?: app()->getLocale());

        $array['title'] = $translation ? $translation->title : '';

        $array['file_url'] = $this->file ? Storage::url($this->file) : null;

        $array['sample_file_url'] = $this->sample_file ? Storage::url($this->sample_file) : null;

        return $array;
    }
}