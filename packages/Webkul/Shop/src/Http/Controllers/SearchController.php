<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\MagicAI\Facades\MagicAI;
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
     * @return View
     */
    public function index()
    {
        $this->validate(request(), [
            'query' => ['sometimes', 'required', 'string', 'regex:/^[^\\\\]+$/u'],
        ]);

        $searchTerm = $this->searchTermRepository->findOneWhere([
            'term' => request()->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale' => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url) {
            return redirect()->to($searchTerm->redirect_url);
        }

        $query = request()->query('query');

        $suggestion = null;

        if (
            ! request()->has('suggest')
            || request()->query('suggest') !== '0'
        ) {
            $searchEngine = core()->getConfigData('catalog.products.search.engine') === 'elastic'
                ? core()->getConfigData('catalog.products.search.storefront_mode')
                : 'database';

            $suggestion = $this->searchRepository
                ->setSearchEngine($searchEngine)
                ->getSuggestions($query);
        }

        return view('shop::search.index', [
            'query' => $query,
            'suggestion' => $suggestion,
            'params' => [
                'sort' => request()->query('sort'),
                'limit' => request()->query('limit'),
                'mode' => request()->query('mode'),
            ],
        ]);
    }

    /**
     * Upload image and analyze it for product search keywords.
     */
    public function upload(): JsonResponse
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageUrl = $this->searchRepository->uploadSearchImage(request()->all());

        $keywords = '';

        $useAi = core()->getConfigData('magic_ai.general.settings.enabled')
            && core()->getConfigData('magic_ai.storefront_features.image_search.enabled');

        if ($useAi) {
            try {
                $keywords = MagicAI::analyzeImage(
                    request()->file('image')->getRealPath()
                );
            } catch (\Exception $e) {
                report($e);

                $useAi = false;
            }
        }

        return response()->json([
            'image_url' => $imageUrl,
            'keywords' => $keywords,
            'engine' => $useAi ? 'ai' : 'tensorflow',
        ]);
    }
}
