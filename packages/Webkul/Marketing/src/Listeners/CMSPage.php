<?php

namespace Webkul\Marketing\Listeners;

use Webkul\CMS\Repositories\PageRepository;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository)
    {
    }
}