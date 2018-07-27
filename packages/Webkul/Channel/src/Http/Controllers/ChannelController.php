<?php

namespace Webkul\Channel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Channel\Repositories\ChannelRepository as Channel;


/**
 * Channel controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
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
     * @var array
     */
    protected $channel;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Channel\Repositories\ChannelRepository  $channel
     * @return void
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
            'default_locale' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency' => 'required'
        ]);


        $this->channel->create(request()->all());

        session()->flash('success', 'Channel created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $channel = $this->channel->findOrFail($id, ['*'], ['locales', 'currencies']);

        return view($this->_config['view'], compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:channels,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'default_locale' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency' => 'required'
        ]);

        $this->channel->update(request()->all(), $id);

        session()->flash('success', 'Channel updated successfully.');

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
        //
    }
}