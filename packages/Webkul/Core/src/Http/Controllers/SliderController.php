<?php

namespace Webkul\Core\Http\Controllers;

use Webkul\Core\Repositories\SliderRepository;

/**
 * Slider controller for managing the slider controls.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SliderRepository
     *
     * Object
     */
    protected $sliderRepository;

    /**
     * @var array
     */
    protected $channels;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository $sliderRepository
     * @return void
     */
    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;

        $this->_config = request('_config');
    }

    /**
     * Loads the index for the sliders settings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Loads the form for creating slider.
     *
     * @return \Illuminate\View\View
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

        $result = $this->sliderRepository->save(request()->all());

        if ($result)
            session()->flash('success', trans('admin::app.settings.sliders.created-success'));
        else
            session()->flash('success', trans('admin::app.settings.sliders.created-fail'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Edit the previously created slider item.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $slider = $this->sliderRepository->findOrFail($id);

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

        $result = $this->sliderRepository->updateItem(request()->all(), $id);

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
        $slider = $this->sliderRepository->findOrFail($id);

        try {
            $this->sliderRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Slider']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Slider']));
        }

        return response()->json(['message' => false], 400);
    }
}