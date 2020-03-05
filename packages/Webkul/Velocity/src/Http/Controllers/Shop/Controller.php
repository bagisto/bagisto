<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Webkul\Velocity\Helpers\Helper;
use Webkul\Product\Helpers\ProductImage;
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
     * Webkul\Product\Helpers\ProductImage object
     *
     * @var ProductImage
    */
    protected $productImageHelper;

    /**
     * SearchRepository object
     *
     * @var SearchRepository
    */
    protected $searchRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
    */
    protected $productRepository;

    /**
     * ProductRepository object of velocity package
     *
     * @var ProductRepository
     */
    protected $velocityProductRepository;

    /**
     * CategoryRepository object of velocity package
     *
     * @var CategoryRepository
    */
    protected $categoryRepository;

    /**
     * WishlistRepository object
     *
     * @var WishlistRepository
    */
    protected $wishlistRepository;

    /**
     * \Webkul\Velocity\Helpers\Helper object
     *
     * @var Helper
    */
    protected $velocityHelper;

    /**
     * Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository object of repository
     *
     * @var VelocityCustomerCompareProductRepository
    */
    protected $compareProductsRepository;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper $velocityHelper
     * @param  \Webkul\Product\Helpers\ProductImage $productImageHelper
     * @param  \Webkul\Product\Repositories\SearchRepository $searchRepository
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository $categoryRepository
     * @param  \Webkul\Velocity\Repositories\Product\ProductRepository $velocityProductRepository
     * @param  CustomerCompareProductRepository $compareProductsRepository
     *
     * @return void
    */
    public function __construct(
        Helper $velocityHelper,
        ProductImage $productImageHelper,
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
        $this->productImageHelper = $productImageHelper;
        $this->categoryRepository = $categoryRepository;
        $this->wishlistRepository = $wishlistRepository;
        $this->velocityProductRepository = $velocityProductRepository;
        $this->compareProductsRepository = $compareProductsRepository;
    }
}
