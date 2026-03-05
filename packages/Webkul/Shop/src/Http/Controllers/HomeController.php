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
            ->with('translations')
            ->get();        

        // service location shown on hero banner 
        $service_locations = BookingProduct::pluck('location');

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
    $slug = $request->get('slug');
    dd($slug);

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
    public function productDetails($id){
        
        //product id from url 
        $product_id = $id;

        // current channel and locale
        $current_locale = app()->getLocale();
        $current_chanel = core()->getCurrentChannelCode();

        // fetch single product based on current channel and locale
        $productFlat = ProductFlat::where('product_id',$product_id)
                   ->where('locale',$current_locale)
                   ->where('channel',$current_chanel)
                   ->firstOrFail();

        // fetch product images from product_images using has many relationship
        $product_images = Product::with('images')->findOrFail($product_id);
        
        // fetch product images by sorting as per position
        $images = $product_images->images->sortBy('position')->values();

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

    public function languageArabicSwitch($locale){ 
       $channelRepo = app(ChannelRepository::class);
       $channel = core()->getCurrentChannel();
     $availableLocales = core()->getCurrentChannel()->locales->pluck('code')->toArray();
    if (in_array($locale, $availableLocales)) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
    }


    public function languageEnglishSwitch($locale){ 
       $channelRepo = app(ChannelRepository::class);
       $channel = core()->getCurrentChannel();
     $availableLocales = core()->getCurrentChannel()->locales->pluck('code')->toArray();
    if (in_array($locale, $availableLocales)) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
    }

    public function allServices(){
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
    })->get();
    }

        return view('shop::services.index',compact('categories','services'));
    }
    
}
