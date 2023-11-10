<?php

namespace Webkul\Marketing\Listeners;

use Webkul\CMS\Repositories\PageRepository;

class Page
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository)
    {
    }

    /**
     * Before page is created
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function beforeCreate($page)
    {
    }

    /**
     * After page is updated
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function afterUpdate($page)
    {
    }

    /**
     * Before page is deleted
     *
     * @param  integer  $id
     * @return void
     */
    public function beforeDelete($page)
    {
    }
}