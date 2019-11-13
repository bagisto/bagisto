<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;

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
        return 'Webkul\Product\Contracts\Product';
    }

    public function searchAttributes()
    {
    }

    public function search($data) {
        $products = $this->product->searchProductByAttribute($data['term']);

        return $products;
    }
}