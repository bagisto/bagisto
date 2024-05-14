<?php

namespace Webkul\DataGrid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\DataGrid\Contracts\Filter as FilterContract;

class Filter extends Model implements FilterContract
{
    use HasFactory;

    /**
     * Deinfine model table name.
     *
     * @var string
     */
    protected $table = 'data_grid_filters';

    /**
     * Fillable property for the model.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'src',
        'name',
        'applied',
    ];

    protected $casts = [
        'applied' => 'json',
    ];

    /**
     * Get the order record associated with the order comment.
     */
    public function filter()
    {
        return $this->belongsTo(FilterProxy::modelClass());
    }
}
