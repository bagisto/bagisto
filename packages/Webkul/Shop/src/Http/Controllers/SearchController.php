<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Repositories\SearchRepository;
use Illuminate\Http\Request;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Product\Models\ProductFlat;
use Webkul\Category\Models\Category;
use Webkul\BookingProduct\Models\BookingProduct;


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



public function serviceSearchResult(Request $req)
{
    $req->validate([
        'service_category_id' => ['required', 'integer'],
        'service_location' => ['required', 'string'],
        'service_date' => ['required', 'date'],
        'service_time' => ['required', 'date_format:H:i'], // validate time format
    ]);

    $service_category_id = $req->service_category_id;
    $service_location = $req->service_location;
    $service_date = $req->service_date; // YYYY-MM-DD

    // Fetch all root categories and children
    $rootCategory = Category::whereNull('parent_id')->first();
    $categories = Category::where('parent_id', $rootCategory->id)
        ->where('status',1)
        ->with('translations')
        ->get();

    // Fetch services filtered by category, location, and date
    $services = ProductFlat::with(['product.images'])
        ->where('type', 'booking')
        ->where('status', 1)
        ->where('visible_individually', 1)
        ->where('locale', app()->getLocale())
        ->where('channel', core()->getCurrentChannel()->code)
        ->whereHas('product.categories', function ($q) use ($service_category_id) {
            $q->where('category_id', $service_category_id);
        })
        ->whereHas('product.bookingProducts', function ($q) use ($service_location, $service_date) {
            $q->where('location', $service_location)
              ->whereDate('available_from', '<=', $service_date)
              ->whereDate('available_to', '>=', $service_date);
        })
        ->get();

    // Send the selected category id so Blade can mark the active tab
    return view('shop::services.index', compact(
        'services',
        'service_category_id',
        'categories',
    ));
}

}