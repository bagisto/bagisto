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
     * After section create.
     *
     * @param  \Webkul\Theme\Contracts\Section  $section
     * @return void
     */
    public function afterCreate($section)
    {
        $this->forget($section);
    }

    /**
     * After section update.
     *
     * @param  \Webkul\Theme\Contracts\Section  $section
     * @return void
     */
    public function afterUpdate($section)
    {
        $this->forget($section);
    }

    /**
     * Before section delete.
     *
     * @param  int  $sectionId
     * @return void
     */
    public function beforeDelete($sectionId)
    {
        $this->forget($this->sectionRepository->find($sectionId));
    }

    /**
     * Drop the pages a section is rendered on, which is every page for a type the layout draws.
     *
     * @param  \Webkul\Theme\Contracts\Section|null  $section
     */
    protected function forget($section): void
    {
        if ($section?->getTypeInstance()?->rendersInLayout()) {
            ResponseCache::clear();

            return;
        }

        ResponseCache::selectCachedItems()
            ->forUrls(config('app.url').'/')
            ->forget();
    }
}
