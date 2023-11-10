<?php

namespace Webkul\Marketing\Listeners;

use Webkul\Category\Repositories\CategoryRepository;

class Category
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }
}