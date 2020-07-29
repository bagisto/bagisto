<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\SearchRepository;

class HomeController extends Controller
{
    /**
     * SliderRepository object
     *
     * @var \Webkul\Core\Repositories\SliderRepository
    */
    protected $sliderRepository;

    /**
     * SearchRepository object
     *
     * @var \Webkul\Core\Repositories\SearchRepository
    */
    protected $searchRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
     * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
     * @return void
    */
    public function __construct(
        SliderRepository $sliderRepository,
        SearchRepository $searchRepository
    )
    {
        $this->sliderRepository = $sliderRepository;

        $this->searchRepository = $searchRepository;

        parent::__construct();
    }

    /**
     * loads the home page for the storefront
     * 
     * @return \Illuminate\View\View 
     */
    public function index()
    {
        $currentChannel = core()->getCurrentChannel();

        $currentLocale = core()->getCurrentLocale();

        $sliderData = $this->sliderRepository
            ->where('channel_id', $currentChannel->id)
            ->where('locale', $currentLocale->code)
            ->get()
            ->toArray();

        return view($this->_config['view'], compact('sliderData'));
    }

    /**
     * loads the home page for the storefront
     * 
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Upload image for product search with machine learning
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $url = $this->searchRepository->uploadSearchImage(request()->all());

        return $url; 
    }
}