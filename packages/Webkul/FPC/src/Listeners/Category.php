<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
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

    /**
     * After category update
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                ResponseCache::forget($categoryTranslation->slug);
            }

            ResponseCache::forget($category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug);
        }
    }

    /**
     * Before category delete
     *
     * @param  int  $categoryId
     * @return void
     */
    public function beforeDelete($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                ResponseCache::forget($categoryTranslation->slug);
            }

            ResponseCache::forget($category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug);
        }
    }
}
