<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\SearchRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @return void
     */
    public function __construct(
        protected SliderRepository $sliderRepository,
        protected SearchRepository $searchRepository
    )
    {
        parent::__construct();
    }

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        
        $sliderData = $this->sliderRepository->getActiveSliders();
        $guestReviewStatus = core()->getConfigData('catalog.products.review.guest_review');
        $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage') ?: 10;
        $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';

        $config = ['guestReviewStatus' => $guestReviewStatus , 'count' => $count , 'direction' => $direction];

        return view($this->_config['view'], compact('sliderData','config'));
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
