<?php

namespace Webkul\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Notification\Contracts\Notification as NotificationContract;
use Webkul\Sales\Models\OrderProxy;

class Notification extends Model implements NotificationContract
{
    /**
     * The type whose notifications still carry the deprecated order column.
     */
    const ORDER_PLACED = 'order.placed';

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
        'read',
        'order_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
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

    /**
     * The order a placed order notification is about, kept for code written before notifications were typed.
     *
     * @deprecated Read the order's id from the notification's data.
     *
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Fill the deprecated order column of a placed order notification from its data.
     */
    protected static function booted(): void
    {
        static::creating(function (self $notification) {
            if ($notification->type === self::ORDER_PLACED) {
                $notification->order_id ??= $notification->data['order_id'] ?? null;
            }
        });
    }
}
