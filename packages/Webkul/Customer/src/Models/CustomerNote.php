<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CustomerNote as CustomerNoteContract;

class CustomerNote extends Model implements CustomerNoteContract
{
    protected $fillable = [
        'note',
        'customer_id',
        'customer_notified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'customer_notified' => 'boolean',
    ];

    /**
     * Get the order record associated with the order comment.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }
}
