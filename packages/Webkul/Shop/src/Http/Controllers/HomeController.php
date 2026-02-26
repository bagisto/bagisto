<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Http\Resources\CategoryTreeResource;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
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
    public function index(){

    // Find the root category whose slug is 'root'
    $servicesRoot = CategoryTranslation::where('slug', 'root')->first();
    // Get all direct children of that root category
    $childCategories = Category::where('parent_id', $servicesRoot->id)
                           ->with('translations') // eager load translations
                           ->get();
    // Extract names (any translation)
    $categories = $childCategories->map(function ($cat) {
    return [
        'id'   => $cat->id,
        'name' => $cat->name,
        'slug' => $cat->slug,
         ]; 
    });

    $slug =  $req->slug ?? 'facial';

        // 1️⃣ Find category_id using slug
        $category = CategoryTranslation::where('slug', $slug)->first();
        $category_id = $category->category_id;
        
        $services = Product::query()
            ->where('type', 'booking') // correct table
            ->whereHas('categories', function ($q) use ($category_id) {
                  $q->where('category_id', $category_id);})
            ->whereHas('product_flats', function ($q) {$q->where('status', 1)
            ->where('visible_individually', 1)
            ->where('channel', core()->getCurrentChannelCode());
            })->get();


    // Fetching all products from product flat
    $products = ProductFlat::with('product.images')
    ->where('status', 1)
    ->where('visible_individually', 1)
    ->where('type','simple')
    ->where('channel', core()->getCurrentChannelCode()) ->where('locale', app()->getLocale())->get();

        return view('shop::home.index',compact('products','categories','services'));
    }

     public function services(Request $req){

      // Fetching all products from product flat
    $products = ProductFlat::with('product.images')
    ->where('status', 1)
    ->where('type','simple')
    ->where('visible_individually', 1)
    ->where('channel', core()->getCurrentChannelCode()) ->where('locale', app()->getLocale())->get();

        // Find the root category whose slug is 'root'
    $servicesRoot = CategoryTranslation::where('slug', 'root')->first();
    // Get all direct children of that root category
    $childCategories = Category::where('parent_id', $servicesRoot->id)
                           ->with('translations') // eager load translations
                           ->get();
    // Extract names (any translation)
    $categories = $childCategories->map(function ($cat) {
    return [
        'id'   => $cat->id,
        'name' => $cat->name,
        'slug' => $cat->slug,
         ]; 
    });

        $slug =  $req->slug ?? '';

        // 1️⃣ Find category_id using slug
        $category = CategoryTranslation::where('slug', $slug)->first();
        $category_id = $category->category_id;
        
        $services = Product::query()
            ->where('type', 'booking') // correct table
            ->whereHas('categories', function ($q) use ($category_id) {
                  $q->where('category_id', $category_id);})
            ->whereHas('product_flats', function ($q) {$q->where('status', 1)
            ->where('visible_individually', 1)
            ->where('channel', core()->getCurrentChannelCode());
            })->get();
        
        return view('shop::home.index',compact('services','categories','products'));
     }

        public function about()
        {
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
        return view('shop::contact.index');
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
