<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Search Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SearchRepository extends Repository
{
    /**
     * ProductRepository object
     *
     * @return Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        App $app
    )
    {
        parent::__construct($app);

        $this->productRepository = $productRepository;
    }

    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    public function searchAttributes()
    {
    }

    public function search($data) {
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $searchTerm = explode("?", $query);
        $serachQuery = '';

        foreach ($searchTerm as $term) {
            if (strpos($term, 'term') !== false) {
                $serachQuery = last(explode("=", $term));
            }
        }

        $products = $this->productRepository->searchProductByAttribute($serachQuery);

        return $products;
    }
}