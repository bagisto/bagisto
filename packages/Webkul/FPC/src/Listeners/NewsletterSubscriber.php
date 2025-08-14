<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class NewsletterSubscriber
{
    /**
    * After newsletter subscription is created.
    *
    * @param mixed $subscription
    * @return void
    */
    public function beforeCreate()
    {
        ResponseCache::clear();
    }
}