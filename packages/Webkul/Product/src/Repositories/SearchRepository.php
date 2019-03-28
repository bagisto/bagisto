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

    public function searchAttributes() {
    }

    public function search($data) {
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $localeTerm = explode("?", $query);
        $serachQuery = '';

        foreach($localeTerm as $term){
            if (strpos($term, 'term') !== false) {
                $serachQuery = last(explode("=", $term));
            }
        }

        $products = $this->product->searchProductByAttribute($serachQuery);

        return $products;
    }
}