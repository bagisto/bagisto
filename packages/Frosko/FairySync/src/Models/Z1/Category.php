<?php

namespace Frosko\FairySync\Models\Z1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $connection = 'z1';

    use HasFactory;

    protected $fillable = [
        'parent_id',
        'oc_category_id',
        'name',
    ];

    public function parentProducts(): BelongsToMany
    {
        return $this->belongsToMany(ParentProduct::class, 'parent_product_category');
    }
}
