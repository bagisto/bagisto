<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAMessage as RMAMessageContract;

class RMAMessage extends Model implements RMAMessageContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'rma_id',
        'is_admin',
        'attachment_path',
        'attachment',
    ];

    /**
     * Get the RMA that owns the message.
     */
    public function rma()
    {
        return $this->belongsTo(RMA::class);
    }
}