<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Http\Resources\CategoryTreeResource;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Category\Models\Category;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Using const variable for status
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository, protected CategoryRepository $categoryRepository) {}

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
        {
    // Get root category (parent_id = null in Bagisto usually)
    $rootCategory = Category::whereNull('parent_id')->first();

    if (!$rootCategory) {
        return view('shop::home.index', [
            'categories' => collect(),
            'services'   => collect(),
        ]);
    }

    // Get child categories under root
    $categories = Category::where('parent_id', $rootCategory->id)->get();

    // Load services from first category (default)
    $services = collect();

    if ($categories->count()) {

        $firstCategory = $categories->first();

        $services = $firstCategory->products()
            ->where('type', 'booking')
            ->whereHas('product_flats', function ($q) {
                $q->where('status', 1)
                  ->where('visible_individually', 1);
            })
            ->get();
    }
    return view('shop::home.index', compact('categories', 'services'));
    }


public function servicesByCategory(Request $req)
{
     // Get root category (parent_id = null in Bagisto usually)
    $rootCategory = Category::whereNull('parent_id')->first();

    if (!$rootCategory) {
        return view('shop::home.index', [
            'categories' => collect(),
            'services'   => collect(),
        ]);
    }

    // Get child categories under root
    $categories = Category::where('parent_id', $rootCategory->id)->get();
 
    // Step 1: Find translation
    $translation = CategoryTranslation::where('slug', $req->slug)->first();

    if (!$translation) {
        return response()->json([]);
    }

    // Step 2: Get actual category
    $category = Category::find($translation->category_id);

    if (!$category) {
        return response()->json([]);
    }

    // Step 3: Get services
    $services = $category->products()
        ->where('type', 'booking')
        ->whereHas('product_flats', function ($q) {
            $q->where('status', 1)
              ->where('visible_individually', 1);
        })
        ->get();

        return view('shop::home.index', compact('categories', 'services'));
}

    // this will render about page
    public function about(){
        return view('shop::about.index');
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
     * Summary of contact.
     *
     * @return \Illuminate\View\View
     */
    public function contactUs()
    {
        return view('shop::home.contact-us');
    }

    /**
     * Summary of store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactUsMail(ContactRequest $contactRequest)
    {
        try {
            Mail::queue(new ContactUs($contactRequest->only([
                'name',
                'email',
                'contact',
                'message',
            ])));

            session()->flash('success', trans('shop::app.home.thanks-for-contact'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }
}
