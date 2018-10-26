<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Core\Repositories\SliderRepository as Slider;

/**
 * Slider controller for managing the slider controls.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
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
     * Loads the index for the sliders settings.
     *
     * @return mixed
     */
    public function index() {
        return view($this->_config['view']);
    }

    /**
     * Loads the form for creating slider.
     *
     * @return mixed
     */

    public function create() {
        $channels = core()->getAllChannels();

        return view($this->_config['view']);
    }

    /**
     * Creates the new sider item.
     *
     * @return response
     */
    public function store() {

        $this->slider->create(request()->all());

        session()->flash('success', 'Slider created successfully.');

        return redirect()->back();
    }

    /**
     * Edit the previously created slider item.
     *
     * @return mixed
     */
    public function edit($id) {
        $slider = $this->slider->find($id);

        return view($this->_config['view'])->with('slider', $slider);
    }

    /**
     * Edit the previously created slider item.
     *
     * @return response
     */
    public function update($id) {
        if($this->slider->updateItem(request()->all(), $id)) {
            session()->flash('success', 'Slider Item Successfully Updated');
        } else {
            session()->flash('error', 'Slider Cannot Be Updated');
        }

        return redirect()->back();
    }

    /**
     * Delete a slider item and preserve the last one from deleting
     *
     * @return mixed
     */
    public function destroy($id) {
        if($this->slider->findWhere(['channel_id' => core()->getCurrentChannel()->id])->count() == 1) {
            session()->flash('warning', 'Cannot Delete The Last Slider Item');
        } else {
            $this->slider->destroy($id);

            session()->flash('success', 'Slider Item Successfully Deleted');
        }

        return redirect()->back();
    }
}