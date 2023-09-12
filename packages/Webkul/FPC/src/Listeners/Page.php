<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\CMS\Repositories\CmsRepository;

class Page
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\CMS\Repositories\CmsRepository  $pageRepository
     * @return void
     */
    public function __construct(protected CmsRepository $pageRepository)
    {
    }

    /**
     * After page update
     *
     * @param  \Webkul\CMS\Contracts\CmsPage  $page
     * @return void
     */
    public function afterUpdate($page)
    {
        ResponseCache::forget('/page/' .  $page->url_key);
    }

    /**
     * Before page delete
     *
     * @param  integer  $pageId
     * @return void
     */
    public function beforeDelete($pageId)
    {
        $page = $this->pageRepository->find($pageId);

        ResponseCache::forget('/page/' .  $page->url_key);
    }
}
