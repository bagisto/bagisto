<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Core\Repositories\SliderRepository as Slider;

/**
 * Slider controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class SliderController extends controller
{
    protected $_config;
    protected $slider;
    protected $channels;

    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
        $this->_config = request('_config');

    }

    /**
     * Loads the index
     * for the sliders
     * settings.
     */

    public function index() {
        return view($this->_config['view']);
    }
    /**
     * Loads the form
     * for creating
     * slider.
     */

    public function create() {
        $channels = core()->getAllChannels();

        return view($this->_config['view'])->with('channels',[$channels]);
    }

    /**
     * Creates the new
     * sider item
     */
    public function store() {
        $this->slider->create(request()->all());
        session()->flash('success', 'Slider created successfully.');
        return redirect()->back();
    }
}