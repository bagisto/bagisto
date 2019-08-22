<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Webkul\Product\Repositories\SearchRepository as Search;

/**
 * Search controller
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

 class SearchController extends controller
{
    protected $_config;

    protected $search;

    public function __construct(Search $search)
    {
        $this->_config = request('_config');

        $this->search = $search;
    }

    /**
     * Index to handle the view loaded with the search results
     */
    public function index()
    {
        $results = null;

        $results = $this->search->search(request()->all());

        if ($results->count()) {
            return view($this->_config['view'])->with('results', $results);
        } else {
            return view($this->_config['view'])->with('results', null);
        }

    }
}
