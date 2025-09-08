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
    ) {}

    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->validate(request(), [
            'query' => ['sometimes', 'required', 'string', 'regex:/^[^\\\\]+$/u'],
        ]);

        $searchTerm = $this->searchTermRepository->findOneWhere([
            'term'       => request()->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale'     => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url) {
            return redirect()->to($searchTerm->redirect_url);
        }

        return view('shop::search.index', [
            'params' => [
                'sort'  => request()->query('sort'),
                'limit' => request()->query('limit'),
                'mode'  => request()->query('mode'),
            ],
        ]);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return string
     */
    public function upload()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        return $this->searchRepository->uploadSearchImage(request()->all());
    }
}
