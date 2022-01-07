<?php

namespace Webkul\Core\Http\Controllers;

use Webkul\Core\Repositories\SliderRepository;

class SliderController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Slider repository instance.
     *
     * @var \Webkul\Core\Repositories\SliderRepository
     */
    protected $sliderRepository;

    /**
     * Channels.
     *
     * @var array
     */
    protected $channels;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
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
        $locale = core()->getRequestedLocaleCode();

        return view($this->_config['view'])->with("locale", $locale);
    }

    /**
     * Creates the new slider item.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'title'      => 'string|required',
            'channel_id' => 'required',
            'expired_at' => 'nullable|date',
            'image.*'    => 'required|mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $data = request()->all();

        $data['expired_at'] = $data['expired_at'] ?: null;

        if (isset($data['locale'])) {
            $data['locale'] = implode(',', $data['locale']);
        }

        $result = $this->sliderRepository->save($data);

        if ($result) {
            session()->flash('success', trans('admin::app.settings.sliders.created-success'));
        } else {
            session()->flash('success', trans('admin::app.settings.sliders.created-fail'));
        }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'title'      => 'string|required',
            'channel_id' => 'required',
            'expired_at' => 'nullable|date',
            'image.*'    => 'sometimes|mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $data = request()->all();
        
        $data['expired_at'] = $data['expired_at'] ?: null;

        if (isset($data['locale'])) {
            $data['locale'] = implode(',', $data['locale']);
        }

        if (is_null(request()->image)) {
            session()->flash('error', trans('admin::app.settings.sliders.update-fail'));

            return redirect()->back();
        }

        $result = $this->sliderRepository->updateItem($data, $id);

        if ($result) {
            session()->flash('success', trans('admin::app.settings.sliders.update-success'));
        } else {
            session()->flash('error', trans('admin::app.settings.sliders.update-fail'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete the slider item and preserve the last one from deleting.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->sliderRepository->findOrFail($id);

        try {
            $this->sliderRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Slider']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Slider']));
        }

        return response()->json(['message' => false], 400);
    }
}
