<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Repositories\SearchRepository;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SearchTermRepository $searchTermRepository,
        protected SearchRepository $searchRepository
    ) {
    }

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $searchTerm = $this->searchTermRepository->findOneWhere([
            'term'       => request()->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale'     => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url) {
            return redirect()->to($searchTerm->redirect_url);
        }

        return view('shop::search.index');
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
