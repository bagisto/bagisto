<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container;
use Webkul\Product\Repositories\ProductRepository;
use Prettus\Repository\Traits\CacheableRepository;

class ContentRepository extends Repository
{
    use CacheableRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Velocity\Contracts\Content';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Velocity\Models\Content
     */
    public function create(array $data)
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                    }
                }
            }
        }

        $content = $this->model->create($data);

        return $content;
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function getProducts($id)
    {
        $results = [];

        $locale = core()->getRequestedLocaleCode();

        $content = $this->model->find($id);

        if ($content->content_type == 'product') {
            $contentLocale = $content->translate($locale);

            $products = json_decode($contentLocale->products, true);

            if (! empty($products)) {
                foreach ($products as $product_id) {
                    $product = $this->productRepository->find($product_id);

                    if (isset($product->id)) {
                        $results[] = [
                            'id'   => $product->id,
                            'name' => $product->name,
                        ];
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public function getAllContents()
    {
        $query = $this->model::orderBy('position', 'ASC');

        $velocityMetaData = app('Webkul\Velocity\Helpers\Helper')->getVelocityMetaData();
        $headerContentCount = $velocityMetaData->header_content_count ?? '';

        $headerContentCount = $headerContentCount != '' ? $headerContentCount : 5;

        $contentCollection = $query
            ->select(
                'velocity_contents.content_type',
                'velocity_contents_translations.title as title',
                'velocity_contents_translations.page_link as page_link',
                'velocity_contents_translations.link_target as link_target'
            )
            ->where('velocity_contents.status', 1)
            ->leftJoin('velocity_contents_translations', 'velocity_contents.id', 'velocity_contents_translations.content_id')
            ->distinct('velocity_contents_translations.id')
            ->where('velocity_contents_translations.locale', app()->getLocale())
            ->limit($headerContentCount)
            ->get();

        $formattedContent = [];

        foreach ($contentCollection as $content) {
            array_push($formattedContent, [
                'title'        => $content->title,
                'page_link'    => $content->page_link,
                'link_target'  => $content->link_target,
                'content_type' => $content->content_type,
            ]);
        }

        return $formattedContent;
    }
}