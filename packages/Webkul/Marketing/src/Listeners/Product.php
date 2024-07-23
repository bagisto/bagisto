<?php

namespace Webkul\Marketing\Listeners;

use Illuminate\Support\Facades\Event;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product
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
        protected ProductRepository $productRepository,
        protected URLRewriteRepository $urlRewriteRepository
    ) {}

    /**
     * After product is updated
     *
     * @param  int  $id
     * @return void
     */
    public function beforeUpdate($id)
    {
        $currentURLKey = request()->input('url_key');

        if (! $currentURLKey) {
            return;
        }

        $product = $this->productRepository->find($id);

        if ($currentURLKey === $product->url_key) {
            return;
        }

        if (empty($product->url_key)) {
            /**
             * Delete category and product url rewrites
             * if already exists for the request path
             */
            $urlRewrites = $this->urlRewriteRepository->findWhere([
                ['entity_type', 'IN', ['category', 'product']],
                'request_path' => $currentURLKey,
            ]);

            foreach ($urlRewrites as $urlRewrite) {
                Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $urlRewrite->id);

                $this->urlRewriteRepository->delete($urlRewrite->id);

                Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $urlRewrite->id);
            }

            return;
        }

        /**
         * Delete category and product url rewrites
         * if already exists for the request path
         */
        $urlRewrites = $this->urlRewriteRepository->findWhere([
            ['entity_type', 'IN', ['category', 'product']],
            'target_path' => $product->url_key,
        ]);

        foreach ($urlRewrites as $urlRewrite) {
            Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $urlRewrite->id);

            $this->urlRewriteRepository->delete($urlRewrite->id);

            Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $urlRewrite->id);
        }

        Event::dispatch('marketing.search_seo.url_rewrites.create.before');

        $urlRewrite = $this->urlRewriteRepository->create([
            'entity_type'   => 'product',
            'request_path'  => $product->url_key,
            'target_path'   => $currentURLKey ?? '',
            'locale'        => app()->getLocale(),
            'redirect_type' => self::PERMANENT_REDIRECT_CODE,
        ]);

        Event::dispatch('marketing.search_seo.url_rewrites.create.after', $urlRewrite);
    }

    /**
     * Before product is deleted
     *
     * @param  int  $id
     * @return void
     */
    public function beforeDelete($id)
    {
        $product = $this->productRepository->find($id);

        /**
         * Delete product url rewrites
         * if already exists for the request path
         */
        $urlRewrites = $this->urlRewriteRepository->findWhere([
            'entity_type'  => 'product',
            'request_path' => $product->url_key,
        ]);

        foreach ($urlRewrites as $urlRewrite) {
            Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $urlRewrite->id);

            $this->urlRewriteRepository->delete($urlRewrite->id);

            Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $urlRewrite->id);
        }
    }
}
