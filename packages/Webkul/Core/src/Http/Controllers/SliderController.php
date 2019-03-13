<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\SliderRepository as Slider;

/**
 * Slider controller for managing the slider controls.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderController extends controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SliderRepository object
     * Object
     */
    protected $slider;

    /**
     * @var array
     */
    protected $channels;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\SliderRepository $slider
     * @return void
     */
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
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Loads the form for creating slider.
     *
     * @return mixed
     */
    public function create()
    {
        $channels = core()->getAllChannels();

        return view($this->_config['view']);
    }

    /**
     * Creates the new sider item.
     *
     * @return response
     */
    public function store()
    {
        $this->validate(request(), [
            'title' => 'string|required',
            'channel_id' => 'required',
            'image.*'  => 'required|mimes:jpeg,bmp,png,jpg'
        ]);

        $result = $this->slider->save(request()->all());

        if ($result)
            session()->flash('success', trans('admin::app.settings.sliders.created-success'));
        else
            session()->flash('success', trans('admin::app.settings.sliders.created-fail'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Edit the previously created slider item.
     *
     * @return mixed
     */
    public function edit($id)
    {
        $slider = $this->slider->find($id);

        return view($this->_config['view'])->with('slider', $slider);
    }

    /**
     * Edit the previously created slider item.
     *
     * @return response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'title' => 'string|required',
            'channel_id' => 'required',
            'image.*'  => 'sometimes|mimes:jpeg,bmp,png,jpg'
        ]);

        $result = $this->slider->updateItem(request()->all(), $id);

        if ($result) {
            session()->flash('success', trans('admin::app.settings.sliders.update-success'));
        } else {
            session()->flash('error', trans('admin::app.settings.sliders.update-fail'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete a slider item and preserve the last one from deleting
     *
     * @return mixed
     */
    public function destroy($id)
    {
        if ($this->slider->findWhere(['channel_id' => core()->getCurrentChannel()->id])->count() == 1) {
            session()->flash('warning', trans('admin::app.settings.sliders.delete-success'));
        } else {
            $this->slider->destroy($id);

            session()->flash('success', trans('admin::app.settings.sliders.delete-fail'));
        }

        return redirect()->back();
    }
}