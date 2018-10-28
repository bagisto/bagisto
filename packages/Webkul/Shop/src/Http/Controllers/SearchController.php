<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Search controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

 class SearchController extends controller
{
    protected $_config;
    protected $sliders;
    protected $current_channel;

    public function __construct(Sliders $s)
    {
        $this->_config = request('_config');

        $this->sliders = $s;
    }

    /**
     * Index to handle the view loaded with the search results
     */
    public function index($data) {
        return redirect()->back();
    }
}
