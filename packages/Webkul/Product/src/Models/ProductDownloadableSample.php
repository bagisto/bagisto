<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductDownloadableSample as ProductDownloadableSampleContract;

class ProductDownloadableSample extends TranslatableModel implements ProductDownloadableSampleContract
{
    public $translatedAttributes = ['title'];

    protected $fillable = ['url', 'file', 'file_name', 'type', 'sort_order', 'product_id'];

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
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();


        $translation = $this->translate(request()->get('locale') ?: app()->getLocale());

        $array['title'] = $translation ? $translation->title : '';

        $array['file_url'] = $this->file ? Storage::url($this->file) : null;

        return $array;
    }
}