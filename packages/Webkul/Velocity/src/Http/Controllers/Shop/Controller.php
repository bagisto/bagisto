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
     * SearchRepository object
     *
     * @var \Webkul\Product\Repositories\SearchRepository
     */
    protected $searchRepository;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * ProductRepository object of velocity package
     *
     * @var \Webkul\Velocity\Repositories\Product\ProductRepository
     */
    protected $velocityProductRepository;

    /**
     * CategoryRepository object of velocity package
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * WishlistRepository object
     *
     * @var \Webkul\Customer\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * Helper object
     *
     * @var \Webkul\Velocity\Helpers\Helper
     */
    protected $velocityHelper;

    /**
     * VelocityCustomerCompareProductRepository object of repository
     *
     * @var \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository
     */
    protected $compareProductsRepository;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper                                         $velocityHelper
     * @param  \Webkul\Product\Repositories\SearchRepository                           $searchRepository
     * @param  \Webkul\Product\Repositories\ProductRepository                          $productRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository                        $categoryRepository
     * @param  \Webkul\Velocity\Repositories\Product\ProductRepository                 $velocityProductRepository
     * @param  \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository  $compareProductsRepository
     *
     * @return void
     */
    public function __construct(
        Helper $velocityHelper,
        SearchRepository $searchRepository,
        ProductRepository $productRepository,
        WishlistRepository $wishlistRepository,
        CategoryRepository $categoryRepository,
        VelocityProductRepository $velocityProductRepository,
        CustomerCompareProductRepository $compareProductsRepository
    ) {
        $this->_config = request('_config');

        $this->velocityHelper = $velocityHelper;

        $this->searchRepository = $searchRepository;

        $this->productRepository = $productRepository;

        $this->categoryRepository = $categoryRepository;

        $this->wishlistRepository = $wishlistRepository;

        $this->velocityProductRepository = $velocityProductRepository;

        $this->compareProductsRepository = $compareProductsRepository;
    }
}
