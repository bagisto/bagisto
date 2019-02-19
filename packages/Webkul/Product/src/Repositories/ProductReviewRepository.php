<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Product Review Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductReviewRepository extends Repository
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

    /**
     * Retrieve review for customerId
     *
     * @param int $customerId
     */
    function getCustomerReview()
    {
        $customerId = auth()->guard('customer')->user()->id;

        $reviews = $this->model->where(['customer_id'=> $customerId])->with('product')->get();

        return $reviews;
    }
}