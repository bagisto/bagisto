<?php

namespace Webkul\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Notification\Contracts\NotificationRead as NotificationReadContract;

class NotificationRead extends Model implements NotificationReadContract
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_id',
        'reader_type',
        'reader_id',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * The notification that was read.
     */
    public function notification(): BelongsTo
    {
        return $this->belongsTo(NotificationProxy::modelClass());
    }

    /**
     * The admin, seller or other user who read it.
     */
    public function reader(): MorphTo
    {
        return $this->morphTo();
    }
}
