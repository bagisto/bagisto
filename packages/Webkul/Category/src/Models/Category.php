<?php

namespace Webkul\Category\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Contracts\Category as CategoryContract;

class Category extends TranslatableModel implements CategoryContract
{
    use NodeTrait;

    public $translatedAttributes = ['name', 'description', 'slug', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $fillable = ['position', 'status', 'parent_id'];

    /**
     * Get image url for the category image.
     */
    public function image_url()
    {
        if (! $this->image)
            return;

        return Storage::url($this->image);
    }

    /**
     * Get image url for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }
}