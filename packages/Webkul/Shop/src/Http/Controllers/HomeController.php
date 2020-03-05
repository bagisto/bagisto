<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;

 class HomeController extends Controller
{
    /**
     * SliderRepository object
     *
     * @var \Webkul\Core\Repositories\SliderRepository
    */
    protected $sliderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
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
     * 
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }
}