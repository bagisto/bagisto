<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Channel\Channel;
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

    public function __construct()
    {
        $this->_config = request('_config');

    }

    public function index(){
        $call = new Channel();
        $channels = $call->getChannelWithLocales();
        return view($this->_config['view'])->with('channels',[$channels]);
    }

    public function create(Request $request) {
        $this->validate($request,[
            'title' => 'string|required|max:100',
            'image' => 'required|image|mimes:png,jpg',
            // |dimensions:ratio=12/5
            'content' => 'string'
        ]);

        $image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);


    }
}