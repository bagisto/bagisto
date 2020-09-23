<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container as App;
use Webkul\Product\Repositories\ProductRepository;
use Prettus\Repository\Traits\CacheableRepository;

class ContentRepository extends Repository
{
    use CacheableRepository;

   /**
    * Product Repository object
    *
    * @var \Webkul\Product\Repositories\ProductRepository
    */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        App $app
    )
    {
        $this->productRepository = $productRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Velocity\Contracts\Content';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Velocity\Models\Content
     */
    public function create(array $data)
    {
        // Event::fire('velocity.content.create.before');

        if (isset($data['locale']) && $data['locale'] == 'all') {
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

        // Event::fire('velocity.content.create.after', $content);

        return $content;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @return \Webkul\Velocity\Models\Content
     */
    public function update(array $data, $id)
    {
        $content = $this->find($id);

        // Event::fire('velocity.content.update.before', $id);

        $content->update($data);

        // Event::fire('velocity.content.update.after', $id);

        return $content;
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function getProducts($id)
    {
        $results = [];

        $locale = request()->get('locale') ?: app()->getLocale();

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