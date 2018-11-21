<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribersList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'subscribers_list';

    protected $fillable = [
        'email', 'is_subscribed', 'token', 'channel_id'
    ];

    protected $hidden = ['token'];
}