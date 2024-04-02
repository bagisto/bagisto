<?php

namespace Webkul\Sitemap\Repositories;

use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sitemap\Models\Category;
use Webkul\Sitemap\Models\Page;
use Webkul\Sitemap\Models\Product;

class SitemapRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Sitemap\Contracts\Sitemap';
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $sitemap = parent::create($attributes);

        $this->generateSitemap($sitemap);

        return $sitemap;
    }

    /**
     * Update.
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $sitemap = $this->find($id);

        Storage::delete($sitemap->path.'/'.$sitemap->file_name);

        $sitemap = parent::update($attributes, $id);

        $this->generateSitemap($sitemap);

        return $sitemap;
    }

    /**
     * Update.
     *
     * @param  \Webkul\Sitemap\Contracts\Sitemap  $sitemap
     * @return void
     */
    public function generateSitemap($sitemap)
    {
        Sitemap::create()
            ->add(Url::create('/'))
            ->add(Category::all())
            ->add(Product::all())
            ->add(Page::all())
            ->writeToDisk('public', $sitemap->path.'/'.$sitemap->file_name);
    }
}
