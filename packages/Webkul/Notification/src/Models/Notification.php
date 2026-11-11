<?php

namespace Webkul\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Notification\Contracts\Notification as NotificationContract;

class Notification extends Model implements NotificationContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'audience',
        'data',
        'notifiable_type',
        'notifiable_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The user the notification is addressed to, or none when it is for its whole audience.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Who has read the notification.
     */
    public function reads(): HasMany
    {
        return $this->hasMany(NotificationReadProxy::modelClass());
    }
}
