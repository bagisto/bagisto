<?php

namespace Webkul\Category\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Attribute\Models\AttributeProxy;

/**
 * Class Category
 *
 * @package Webkul\Category\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class Category extends TranslatableModel implements CategoryContract
{
    use NodeTrait;

    public $translatedAttributes = ['name', 'description', 'slug', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $fillable = ['position', 'status', 'display_mode', 'parent_id'];

    protected $with = ['translations'];

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

     /**
     * The filterable attributes that belong to the category.
     */
    public function filterableAttributes()
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')->with('options');
    }
}