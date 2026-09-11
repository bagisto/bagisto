<?php

namespace Webkul\FPC\Listeners;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\FPC\Concerns\ForgetsPages;

class Category
{
    use ForgetsPages;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository) {}

    /**
     * After category create
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterCreate($category)
    {
        $this->forgetPages([$this->homePath()]);
    }

    /**
     * After category update
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        $this->forgetPages($this->forgettablePaths($category));
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

        if (! $category) {
            return;
        }

        $this->forgetPages($this->forgettablePaths($category));
    }

    /**
     * The category's own page in every locale, and the home page it is listed on.
     *
     * A category is drawn on the home page as well as at its own address, so dropping only its
     * slug leaves the carousel there showing the name, image and link it had before.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     */
    protected function forgettablePaths($category): array
    {
        $paths = [$this->homePath()];

        foreach (core()->getAllLocales() as $locale) {
            if ($translation = $category->translate($locale->code)) {
                $paths[] = '/'.$translation->slug;
            }
        }

        return $paths;
    }
}
