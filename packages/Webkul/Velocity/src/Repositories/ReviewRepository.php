<?php

namespace Webkul\Velocity\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Review Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewRepository extends Repository
{
    /**
     * ProductImageRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository      $product
     * @return void
     */
    public function __construct(
        ProductRepository $product,
        App $app)
    {
        $this->product = $product;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductReview';
    }


    function getAll() 
    {
        $reviews = $this->model->get();
        
        return $reviews;
    }
}