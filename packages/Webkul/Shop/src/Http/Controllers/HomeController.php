<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Product\Repositories\SearchRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SearchRepository $searchRepository)
    {
        parent::__construct();
    }

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
