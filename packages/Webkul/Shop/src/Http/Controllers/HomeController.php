<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;

/**
 * Home page controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
 class HomeController extends Controller
{
    /**
     * SliderRepository object
     *
     * @var Object
    */
    protected $sliderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository $sliderRepository
     * @return void
    */
    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;

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
        
        $sliderData = $this->sliderRepository->findByField('channel_id', $currentChannel->id)->toArray();

        return view($this->_config['view'], compact('sliderData'));
    }

    /**
     * loads the home page for the storefront
     */
    public function notFound()
    {
        abort(404);
    }
}