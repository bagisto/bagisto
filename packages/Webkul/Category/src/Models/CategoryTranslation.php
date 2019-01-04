<?php

namespace Webkul\Category\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name', 'description', 'slug', 'meta_title', 'meta_description', 'meta_keywords'];
}