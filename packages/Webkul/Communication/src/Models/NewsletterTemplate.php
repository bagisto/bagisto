<?php

namespace Webkul\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Communication\Contracts\NewsletterTemplate as NewsletterTemplateContract;

class NewsletterTemplate extends Model implements NewsletterTemplateContract
{
    protected $guarded = [];
}