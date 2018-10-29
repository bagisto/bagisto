<?php

namespace Webkul\Core\Repositories;

use Illuminate\Container\Container as App;

use Webkul\Core\Eloquent\Repository;

use Webkul\Product\Repositories\ProductRepository as Product;

// use Webkul\Product\Contracts\Criteria\searchByAttributeCriteria;

/**
 * Search Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SearchRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    protected $product;


    public function __construct(App $app, Product $product) {
        parent::__construct($app);

        $this->product = $product;
    }

    function model()
    {
        return 'Webkul\Product\Models\Product';
    }

    public function searchAttributes(){
        // $this->pushCriteria(app(searchByAttributeCriteria::class));
    }

    public function search($data) {
        $term  = $data['term'];

        $products = $this->product->searchProductByAttribute($term);

        dd($products);
    }
}