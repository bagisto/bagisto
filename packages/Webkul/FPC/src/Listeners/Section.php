<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Theme\Repositories\SectionRepository;

class Section
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected SectionRepository $sectionRepository) {}

    /**
     * After section create
     *
     * @param  \Webkul\Shop\Contracts\Section  $section
     * @return void
     */
    public function afterCreate($section)
    {
        if (in_array($section->type, ['footer_links', 'services_content'])) {
            ResponseCache::clear();
        } else {
            ResponseCache::selectCachedItems()
                ->forUrls(config('app.url').'/')
                ->forget();
        }
    }

    /**
     * After section update
     *
     * @param  \Webkul\Shop\Contracts\Section  $section
     * @return void
     */
    public function afterUpdate($section)
    {
        if (in_array($section->type, ['footer_links', 'services_content'])) {
            ResponseCache::clear();
        } else {
            ResponseCache::selectCachedItems()
                ->forUrls(config('app.url').'/')
                ->forget();
        }
    }

    /**
     * Before section delete
     *
     * @param  int  $sectionId
     * @return void
     */
    public function beforeDelete($sectionId)
    {
        $section = $this->sectionRepository->find($sectionId);

        if (in_array($section->type, ['footer_links', 'services_content'])) {
            ResponseCache::clear();
        } else {
            ResponseCache::selectCachedItems()
                ->forUrls(config('app.url').'/')
                ->forget();
        }
    }
}
