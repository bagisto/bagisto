<?php

namespace Webkul\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Communication\Contracts\NewsletterQueue as NewsletterQueueContract;

class NewsletterQueue extends Model implements NewsletterQueueContract
{
    protected $guarded = [];
}