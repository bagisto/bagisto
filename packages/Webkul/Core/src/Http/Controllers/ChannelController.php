<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\ChannelRepository;

/**
 * Channel controller
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ChannelController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ChannelRepository object
     *
     * @var Object
     */
    protected $channelRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository $channelRepository
     * @return void
     */
    public function __construct(ChannelRepository $channelRepository)
    {
        $this->channelRepository = $channelRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:channels,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'default_locale_id' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency_id' => 'required',
            'root_category_id' => 'required',
            'logo.*' => 'mimes:jpeg,jpg,bmp,png',
            'favicon.*' => 'mimes:jpeg,jpg,bmp,png',
            'seo_title' => 'required|string',
            'seo_description' => 'required|string',
            'seo_keywords' => 'required|string'
        ]);

        $data = request()->all();

        $data['seo']['meta_title'] = $data['seo_title'];
        $data['seo']['meta_description'] = $data['seo_description'];
        $data['seo']['meta_keywords'] = $data['seo_keywords'];

        unset($data['seo_title']);
        unset($data['seo_description']);
        unset($data['seo_keywords']);

        $data['home_seo'] = json_encode($data['seo']);

        unset($data['seo']);

        Event::fire('core.channel.create.before');

        $channel = $this->channelRepository->create($data);

        Event::fire('core.channel.create.after', $channel);

        session()->flash('success', trans('admin::app.settings.channels.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $channel = $this->channelRepository->with(['locales', 'currencies'])->findOrFail($id);

        return view($this->_config['view'], compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:channels,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'inventory_sources' => 'required|array|min:1',
            'default_locale_id' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency_id' => 'required',
            'root_category_id' => 'required',
            'logo.*' => 'mimes:jpeg,jpg,bmp,png',
            'favicon.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        $data = request()->all();

        $data['seo']['meta_title'] = $data['seo_title'];
        $data['seo']['meta_description'] = $data['seo_description'];
        $data['seo']['meta_keywords'] = $data['seo_keywords'];

        unset($data['seo_title']);
        unset($data['seo_description']);
        unset($data['seo_keywords']);

        $data['home_seo'] = json_encode($data['seo']);

        Event::fire('core.channel.update.before', $id);

<<<<<<< HEAD
        $channel = $this->channelRepository->update(request()->all(), $id);
=======
        $channel = $this->channel->update($data, $id);
>>>>>>> f9580b077a856af56b51d8ffbbff524557b317ee

        Event::fire('core.channel.update.after', $channel);

        session()->flash('success', trans('admin::app.settings.channels.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $channel = $this->channelRepository->findOrFail($id);

        if ($channel->code == config('app.channel')) {
            session()->flash('error', trans('admin::app.settings.channels.last-delete-error'));
        } else {
            try {
                Event::fire('core.channel.delete.before', $id);

                $this->channelRepository->delete($id);

                Event::fire('core.channel.delete.after', $id);

                session()->flash('success', trans('admin::app.settings.channels.delete-success'));

                return response()->json(['message' => true], 200);
            } catch(\Exception $e) {
                // session()->flash('warning', trans($e->getMessage()));
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Channel']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}