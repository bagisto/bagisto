<?php

namespace Webkul\FPC\Listeners;

use Webkul\CMS\Repositories\PageRepository;
use Webkul\FPC\Concerns\ForgetsPages;

class Page
{
    use ForgetsPages;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository) {}

    /**
     * After page update.
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function afterUpdate($page)
    {
        $this->forgetPages($this->forgettablePaths($page));
    }

    /**
     * Before page delete.
     *
     * @param  int  $pageId
     * @return void
     */
    public function beforeDelete($pageId)
    {
        $page = $this->pageRepository->find($pageId);

        if (! $page) {
            return;
        }

        $this->forgetPages($this->forgettablePaths($page));
    }

    /**
     * The page's address in every locale, since each translation carries its own url key.
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     */
    protected function forgettablePaths($page): array
    {
        return $page->translations
            ->pluck('url_key')
            ->filter()
            ->map(fn ($urlKey) => '/page/'.$urlKey)
            ->all();
    }
}
