<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleTranslation as CartRuleTranslationContract;

class CartRuleTranslation extends Model implements CartRuleTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['label'];
}