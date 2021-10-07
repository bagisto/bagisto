<?php

// Controllers
use Webkul\API\Http\Controllers\Shop\AddressController;
use Webkul\API\Http\Controllers\Shop\CartController;
use Webkul\API\Http\Controllers\Shop\CategoryController;
use Webkul\API\Http\Controllers\Shop\CheckoutController;
use Webkul\API\Http\Controllers\Shop\CoreController;
use Webkul\API\Http\Controllers\Shop\CustomerController;
use Webkul\API\Http\Controllers\Shop\ForgotPasswordController;
use Webkul\API\Http\Controllers\Shop\InvoiceController;
use Webkul\API\Http\Controllers\Shop\OrderController;
use Webkul\API\Http\Controllers\Shop\ProductController;
use Webkul\API\Http\Controllers\Shop\ResourceController;
use Webkul\API\Http\Controllers\Shop\ReviewController;
use Webkul\API\Http\Controllers\Shop\SessionController;
use Webkul\API\Http\Controllers\Shop\TransactionController;
use Webkul\API\Http\Controllers\Shop\WishlistController;

// Repositories
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

// Resources
use Webkul\API\Http\Resources\Catalog\Attribute;
use Webkul\API\Http\Resources\Catalog\AttributeFamily;
use Webkul\API\Http\Resources\Catalog\Category;
use Webkul\API\Http\Resources\Catalog\ProductReview;
use Webkul\API\Http\Resources\Core\Channel;
use Webkul\API\Http\Resources\Core\Country;
use Webkul\API\Http\Resources\Core\Currency;
use Webkul\API\Http\Resources\Core\Locale;
use Webkul\API\Http\Resources\Core\Slider;
use Webkul\API\Http\Resources\Customer\Customer;
use Webkul\API\Http\Resources\Customer\CustomerAddress;
use Webkul\API\Http\Resources\Customer\Wishlist;
use Webkul\API\Http\Resources\Sales\Invoice;
use Webkul\API\Http\Resources\Sales\Order;
use Webkul\API\Http\Resources\Sales\OrderTransaction;
use Webkul\API\Http\Resources\Sales\Shipment;

Route::group(['prefix' => 'api'], function ($router) {

    Route::group(['middleware' => ['locale', 'theme', 'currency']], function ($router) {
        //Currency and Locale switcher
        Route::get('switch-currency', [CoreController::class, 'switchCurrency']);

        Route::get('switch-locale', [CoreController::class, 'switchLocale']);


        //Category routes
        Route::get('categories', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CategoryRepository::class,
            'resource' => Category::class,
        ]);

        Route::get('descendant-categories', [CategoryController::class, 'index']);

        Route::get('categories/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => CategoryRepository::class,
            'resource' => Category::class,
        ]);


        //Attribute routes
        Route::get('attributes', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => AttributeRepository::class,
            'resource' => Attribute::class,
        ]);

        Route::get('attributes/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => AttributeRepository::class,
            'resource' => Attribute::class,
        ]);


        //AttributeFamily routes
        Route::get('families', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => AttributeFamilyRepository::class,
            'resource' => AttributeFamily::class,
        ]);

        Route::get('families/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => AttributeFamilyRepository::class,
            'resource' => AttributeFamily::class,
        ]);


        //Product routes
        Route::get('products', [ProductController::class, 'index']);

        Route::get('products/{id}', [ProductController::class, 'get']);

        Route::get('product-additional-information/{id}', [ProductController::class, 'additionalInformation']);

        Route::get('product-configurable-config/{id}', [ProductController::class, 'configurableConfig']);


        //Product Review routes
        Route::get('reviews', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => ProductReviewRepository::class,
            'resource' => ProductReview::class,
        ]);

        Route::get('reviews/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => ProductReviewRepository::class,
            'resource' => ProductReview::class,
        ]);

        Route::post('reviews/{id}/create', [ReviewController::class, 'store']);

        Route::delete('reviews/{id}', [ResourceController::class, 'destroy'])->defaults('_config', [
            'repository' => ProductReviewRepository::class,
            'resource' => ProductReview::class,
            'authorization_required' => true
        ]);


        //Channel routes
        Route::get('channels', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => ChannelRepository::class,
            'resource' => Channel::class,
        ]);

        Route::get('channels/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => ChannelRepository::class,
            'resource' => Channel::class,
        ]);


        //Locale routes
        Route::get('locales', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => LocaleRepository::class,
            'resource' => Locale::class,
        ]);

        Route::get('locales/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => LocaleRepository::class,
            'resource' => Locale::class,
        ]);


        //Country routes
        Route::get('countries', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CountryRepository::class,
            'resource' => Country::class,
        ]);

        Route::get('countries/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => CountryRepository::class,
            'resource' => Country::class,
        ]);

        Route::get('country-states', [CoreController::class, 'getCountryStateGroup']);


        //Slider routes
        Route::get('sliders', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => SliderRepository::class,
            'resource' => Slider::class,
        ]);

        Route::get('sliders/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => SliderRepository::class,
            'resource' => Slider::class,
        ]);


        //Currency routes
        Route::get('currencies', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CurrencyRepository::class,
            'resource' => Currency::class,
        ]);

        Route::get('currencies/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => CurrencyRepository::class,
            'resource' => Currency::class,
        ]);

        Route::get('config', [CoreController::class, 'getConfig']);


        //Customer routes
        Route::post('customer/login', [SessionController::class, 'create']);

        Route::post('customer/forgot-password', [ForgotPasswordController::class, 'store']);

        Route::get('customer/logout', [SessionController::class, 'destroy']);

        Route::get('customer/get', [SessionController::class, 'get']);

        Route::put('customer/profile', [SessionController::class, 'update']);

        Route::post('customer/register', [CustomerController::class, 'create']);

        Route::get('customers/{id}', [CustomerController::class, 'get'])->defaults('_config', [
            'repository' => CustomerRepository::class,
            'resource' => Customer::class,
            'authorization_required' => true
        ]);


        //Customer Address routes
        Route::get('addresses', [AddressController::class, 'get'])->defaults('_config', [
            'authorization_required' => true
        ]);

        Route::get('addresses/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => CustomerAddressRepository::class,
            'resource' => CustomerAddress::class,
            'authorization_required' => true
        ]);

        Route::delete('addresses/{id}', [ResourceController::class, 'destroy'])->defaults('_config', [
            'repository' => CustomerAddressRepository::class,
            'resource' => CustomerAddress::class,
            'authorization_required' => true
        ]);

        Route::put('addresses/{id}', [AddressController::class, 'update'])->defaults('_config', [
            'authorization_required' => true
        ]);

        Route::post('addresses/create', [AddressController::class, 'store'])->defaults('_config', [
            'authorization_required' => true
        ]);


        //Order routes
        Route::get('orders', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => OrderRepository::class,
            'resource' => Order::class,
            'authorization_required' => true
        ]);

        Route::get('orders/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => OrderRepository::class,
            'resource' => Order::class,
            'authorization_required' => true
        ]);

        Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->defaults('_config', [
            'repository' => OrderRepository::class,
            'resource' => Order::class,
            'authorization_required' => true
        ]);

        //Invoice routes
        Route::get('invoices', [InvoiceController::class, 'index'])->defaults('_config', [
            'repository' => InvoiceRepository::class,
            'resource' => Invoice::class,
            'authorization_required' => true
        ]);

        Route::get('invoices/{id}', [InvoiceController::class, 'get'])->defaults('_config', [
            'repository' => InvoiceRepository::class,
            'resource' => Invoice::class,
            'authorization_required' => true
        ]);


        //Shipment routes
        Route::get('shipments', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => ShipmentRepository::class,
            'resource' => Shipment::class,
            'authorization_required' => true
        ]);

        Route::get('shipments/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository' => ShipmentRepository::class,
            'resource' => Shipment::class,
            'authorization_required' => true
        ]);

        //Transaction routes
        Route::get('transactions', [TransactionController::class, 'index'])->defaults('_config', [
            'repository' => OrderTransactionRepository::class,
            'resource' => OrderTransaction::class,
            'authorization_required' => true
        ]);

        Route::get('transactions/{id}', [TransactionController::class, 'get'])->defaults('_config', [
            'repository' => OrderTransactionRepository::class,
            'resource' => OrderTransaction::class,
            'authorization_required' => true
        ]);

        //Wishlist routes
        Route::get('wishlist', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => WishlistRepository::class,
            'resource' => Wishlist::class,
            'authorization_required' => true
        ]);

        Route::delete('wishlist/{id}', [ResourceController::class, 'destroy'])->defaults('_config', [
            'repository' => WishlistRepository::class,
            'resource' => Wishlist::class,
            'authorization_required' => true
        ]);

        Route::get('move-to-cart/{id}', [WishlistController::class, 'moveToCart']);

        Route::get('wishlist/add/{id}', [WishlistController::class, 'create']);

        //Checkout routes
        Route::group(['prefix' => 'checkout'], function ($router) {
            Route::post('cart/add/{id}', [CartController::class, 'store']);

            Route::get('cart', [CartController::class, 'get']);

            Route::get('cart/empty', [CartController::class, 'destroy']);

            Route::put('cart/update', [CartController::class, 'update']);

            Route::get('cart/remove-item/{id}', [CartController::class, 'destroyItem']);

            Route::post('cart/coupon', [CartController::class, 'applyCoupon']);

            Route::delete('cart/coupon', [CartController::class, 'removeCoupon']);

            Route::get('cart/move-to-wishlist/{id}', [CartController::class, 'moveToWishlist']);

            Route::post('save-address', [CheckoutController::class, 'saveAddress']);

            Route::post('save-shipping', [CheckoutController::class, 'saveShipping']);

            Route::post('save-payment', [CheckoutController::class, 'savePayment']);

            Route::post('check-minimum-order', [CheckoutController::class, 'checkMinimumOrder']);

            Route::post('save-order', [CheckoutController::class, 'saveOrder']);
        });
    });
});