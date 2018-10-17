<?php

namespace Webkul\Category\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Kalnoy\Nestedset\NodeTrait;

class Category extends TranslatableModel
{
    use NodeTrait;

    public $translatedAttributes = ['name', 'description', 'slug', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $fillable = ['position', 'status', 'parent_id','image'];
}