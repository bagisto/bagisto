<?php

namespace Webkul\Velocity\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Content Reposotory
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ContentRepository extends Repository
{   
   /**
    * Product Repository object
    *
    * @var array
    */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
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
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Velocity\Models\Content';
    }

    public function create(array $data)
    {
        //before store of the Content
        Event::fire('velocity.content.create.before');

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

        //after store of the content
        Event::fire('velocity.content.create.after', $content);
        
        return $content;
    }

    public function update(array $data, $id)
    {
        $content = $this->find($id);
        
        //before store of the Content
        Event::fire('velocity.content.update.before', $id);

        $content->update($data);

        //after store of the content
        Event::fire('velocity.content.update.after', $id);
        
        return $content;
    }

    public function getProducts($id)
    {
        $results = [];
        
        $locale = request()->get('locale') ?: app()->getLocale();
                
        $content = $this->model->find($id);
        
        if ($content->content_type == 'product') {
            $contentLocale = $content->translate($locale);
            
            $products = json_decode($contentLocale->products, true);

            if (!empty($products)) {
                foreach ($products as $product_id) {
                    $product = $this->productRepository->find($product_id);
    
                    if ( isset($product->id)) {
                        $results[] = [
                            'id' => $product->id,
                            'name' => $product->name,
                        ];
                    }
                }
            }
        }

        return $results;
    }

    public function getAllContents()
    {
        $content = $this->model::orderBy('position', 'ASC')->where('status', 1)->limit(5)->get();

        return $content;
    }
}