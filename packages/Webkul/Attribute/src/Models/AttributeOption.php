<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class AttributeOption extends Model
{
    use Translatable;
    
    public $translatedAttributes = ['label'];
}