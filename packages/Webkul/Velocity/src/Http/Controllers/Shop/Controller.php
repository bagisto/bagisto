<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Webkul\Velocity\Helpers\Helper;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Velocity\Repositories\Product\ProductRepository as VelocityProductRepository;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository as CustomerCompareProductRepository;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper  $velocityHelper
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Velocity\Repositories\Product\ProductRepository  $velocityProductRepository
     * @param  \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository  $compareProductsRepository
     *
     * @return void
     */
    public function __construct(
        protected Helper $velocityHelper,
        protected SearchRepository $searchRepository,
        protected ProductRepository $productRepository,
        protected WishlistRepository $wishlistRepository,
        protected CategoryRepository $categoryRepository,
        protected VelocityProductRepository $velocityProductRepository,
        protected CustomerCompareProductRepository $compareProductsRepository
    )
    {
        $this->_config = request('_config');
    }
}
