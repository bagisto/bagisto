<?php

namespace Webkul\Shop\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Mail;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Http\Resources\CategoryTreeResource;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Category\Models\Category;
use Illuminate\Http\Request;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\Product;
use Webkul\BookingProduct\Models\BookingProduct;

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


    public function index(Request $req){

        // service list shown on the hero banner
        // Root category
        $rootCategory = Category::whereNull('parent_id')->first();
        // find all categories based on root category 
        $categories = Category::where('parent_id', $rootCategory->id)
            ->where('status',1)
            ->with('translations')
            ->get();        

        // product as a services 
         $services = collect();
        $activeCategorySlug = null;

      // fetching product as a service as per default category
       if ($categories->count()) {

        $firstCategory = $categories->first();
        $activeCategorySlug = $firstCategory->slug;

    $services = ProductFlat::with(['product.images'])
    ->where('type', 'booking')
    ->where('status', 1)
    ->where('visible_individually', 1)
    ->where('locale', app()->getLocale()) // current locale
    ->where('channel', core()->getCurrentChannel()->code) // current channel
    ->whereHas('product.categories', function ($q) use ($firstCategory) {
        $q->where('category_id', $firstCategory->id);
    })
    ->take(4)
    ->get();
     }

    //fetching simple products  
     $products = ProductFlat::with(['product.images'])
    ->where('type', 'simple')
    ->where('status', 1)
    ->where('visible_individually', 1)
    ->where('locale', app()->getLocale()) // current locale
    ->where('channel', core()->getCurrentChannel()->code) // current channel
    ->get();

    $service_locations = BookingProduct::pluck('location');
 
    return view('shop::home.index', compact(
        'service_locations',
        'categories',
        'services',
        'activeCategorySlug',
        'products'
      ));

    }
    

public function servicesByCategory(Request $request)
{
    $slug = $request->slug;

    // Find the category by slug
    $category = CategoryTranslation::where('slug', $slug)->first();

    if (!$category) {
        abort(404, 'Category not found');
    }

    $category_id = $category->category_id;

    // Fetch products belonging to this category
    $services = ProductFlat::where('status', 1) // active products
        ->where('visible_individually', 1)
        ->where('type','booking')
        ->select('id', 'name', 'price', 'url_key', 'product_id')
        ->where('locale', app()->getLocale()) // current locale
        ->where('channel', core()->getCurrentChannel()->code) // current channel   
        ->whereHas('product.categories', function ($q) use ($category_id) {
            $q->where('category_id', $category_id);
        })
        ->take(4)
        ->get();

    // If AJAX request, return JSON
    if ($request->ajax()) {
        $servicesData = $services->map(function($s) {
            return [
                'name' => $s->name,
                'slug' => $s->slug,
                'duration' => $s->duration,
                'price' => core()->currency($s->price),
                'short_description' => $s->short_description,
                'image' => $s->product->images->first() 
                            ? asset('storage/' . $s->product->images->first()->path)
                            : asset('images/placeholder.png'),
            ];
        });

        return response()->json(['services' => $servicesData]);
    }

    // Normal page load data
    $rootCategory = Category::whereNull('parent_id')->first();

    $categories = Category::where('parent_id', $rootCategory->id)
                ->with('translations')
                ->get();     

    $service_locations = BookingProduct::pluck('location');

    $products = ProductFlat::with(['product.images'])
        ->where('type', 'simple')
        ->where('status', 1)
        ->where('visible_individually', 1)
        ->select('id', 'name', 'price', 'url_key', 'product_id')
        ->where('locale', app()->getLocale())
        ->where('channel', core()->getCurrentChannel()->code)
        ->get();

    

    return view('shop::home.index', compact('services','categories','service_locations','products'));
}


    // this will render about page
    public function about(){
        return view('shop::about.index');
    }

     // this will render gallery page
    public function galleryIndex(){
        return view('shop::gallery.index');
    }

    public function servicesDetails($id){
        $service = ProductFlat::find($id)->first();
        return view('shop::service_details.index',compact('service'));
    }

    // Product details page
    public function productDetails($url_key){

        // fetch single product based on current channel and locale
        $productFlat = ProductFlat::with(['product.images'])
                   ->where('url_key',$url_key)
                   ->where('locale',app()->getLocale())
                   ->where('channel',core()->getCurrentChannelCode())
                   ->firstOrFail();
        
        // fetch product images by sorting as per position
        $images = $productFlat->product->images->sortBy('position')->values();

        // Remove first image (main image)
        $otherImages = $images->slice(1)->take(4);

        // dd($otherImages[3]['path']);

        return view('shop::product_details.index',compact('productFlat','otherImages'));
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

public function switchLanguage($locale)
{
    // Get all available locales for the current channel
    $availableLocales = core()->getCurrentChannel()->locales->pluck('code')->toArray();

    // If the requested locale exists in this channel, set it
    if (in_array($locale, $availableLocales)) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }

    // Redirect back to the previous page
    return redirect()->back();
}



// public function serviceSearchResult(){
//        return view('shop::services.index');
// }

}