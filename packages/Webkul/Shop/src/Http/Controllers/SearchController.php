<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\SearchRepository;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected SearchRepository $searchRepository
    )
    {
    }

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $results = [];

        request()->query->add([
            'name'  => request('term'),
            'sort'  => 'created_at',
            'order' => 'desc',
        ]);

        $results = $this->productRepository->getAll();

        return view('shop::search.index')->with('results', $results->count() ? $results : null);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return string
     */
    public function upload()
    {
        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
