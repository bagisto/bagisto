<?php

namespace Webkul\Marketing\Listeners;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Repositories\URLRewriteRepository;

class Category
{
    /**
     * Permanent redirect code
     * 
     * @var int
     */
    const PERMANENT_REDIRECT_CODE = 301;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected URLRewriteRepository $urlRewriteRepository
    )
    {
    }

    /**
     * After category is created
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterCreate($category)
    {
        /**
         * Delete category and product url rewrites
         * if already exists for the request path
         */
        $this->urlRewriteRepository->deleteWhere([
            ['entity_type', 'IN', ['category', 'product']],
            'request_path' => $product->url_key,
        ]);
    }

    /**
     * Before category is updated
     *
     * @param  integer  $id
     * @return void
     */
    public function beforeUpdate($id)
    {
        $locale = request()->input('locale');

        $category = $this->categoryRepository->find($id);

        $translations = $category->translate($locale);

        /**
         * If url key is empty for requested locale then return
         */
        if (empty($translations['slug'])) {
            return;
        }

        $currentURLKey = request()->input($locale . '.slug');

        if ($translations['slug'] === $currentURLKey) {
            return;
        }

        /**
         * Delete category and product url rewrites
         * if already exists for the request path
         */
        $this->urlRewriteRepository->deleteWhere([
            ['entity_type', 'IN', ['category', 'product']],
            'target_path' => $translations['slug'],
            'locale'      => $locale,
        ]);

        $this->urlRewriteRepository->create([
            'entity_type'   => 'category',
            'request_path'  => $translations['slug'],
            'target_path'   => $currentURLKey,
            'locale'        => $locale,
            'redirect_type' => self::PERMANENT_REDIRECT_CODE,
        ]);
    }

    /**
     * Before category is deleted
     *
     * @param  int  $id
     * @return void
     */
    public function beforeDelete($category)
    {
        $category = $this->categoryRepository->find($id);

        /**
         * Delete all url rewrites for all locales
         */
        $translations = $category->getTranslationsArray();

        foreach ($translations as $locale => $translation) {
            $this->urlRewriteRepository->deleteWhere([
                'entity_type'  => 'category',
                'request_path' => $translation['slug'],
                'locale'       => $locale,
            ]);
        }
    }
}
