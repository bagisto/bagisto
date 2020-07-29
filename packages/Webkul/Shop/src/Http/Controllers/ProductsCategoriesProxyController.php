<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Category\Repositories\CategoryRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     *
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    )
    {
        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;

        parent::__construct();
    }

    /**
     * Show product or category view according to locale based url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Exception
     */
    public function index(Request $request)
    {
        $slugOrPath = urldecode(trim($request->getPathInfo(), '/'));

        if (core()->getCurrentLocale()->code === 'en') {

            if (preg_match('/^([a-z0-9-]+\/?)+$/', $slugOrPath)) {

                return $this->getCategoryOrProductView($slugOrPath);
            }

            return $this->showDefaultView();
        } else {

            return $this->getCategoryOrProductView($slugOrPath);
        }
    }

    /**
     * Show default view.
     *
     * @return \Illuminate\View\View
     */
    private function showDefaultView()
    {
        $sliderRepository = app('Webkul\Core\Repositories\SliderRepository');

        $sliderData = $sliderRepository
            ->where('channel_id', core()->getCurrentChannel()->id)
            ->where('locale', core()->getCurrentLocale()->code)
            ->get()
            ->toArray();

        return view('shop::home.index', compact('sliderData'));
    }

    /**
     * Show product or category view. If neither category nor product matches, abort with code 404.
     *
     * @param  string
     * @return \Illuminate\View\View|\Exception
     */
    private function getCategoryOrProductView($slugOrPath)
    {
        if ($category = $this->categoryRepository->findByPath($slugOrPath)) {

            return view($this->_config['category_view'], compact('category'));
        }

        if ($product = $this->productRepository->findBySlug($slugOrPath)) {

            $customer = auth()->guard('customer')->user();

            return view($this->_config['product_view'], compact('product', 'customer'));
        }

        return $this->showDefaultView();
    }
}