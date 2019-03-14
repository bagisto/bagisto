<?php

Route::group(['prefix' => 'api'], function ($router) {
    
    Route::group(['namespace' => 'Webkul\API\Http\Controllers\Shop'], function ($router) {

        //Category routes
        Route::get('categories', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Category\Repositories\CategoryRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\Category'
        ]);

        Route::get('descendants-categories', 'CategoryController@index');

        Route::get('categories/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Category\Repositories\CategoryRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\Category'
        ]);


        //Attribute routes
        Route::get('attributes', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Attribute\Repositories\AttributeRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\Attribute'
        ]);

        Route::get('attributes/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Attribute\Repositories\AttributeRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\Attribute'
        ]);


        //AttributeFamily routes
        Route::get('families', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Attribute\Repositories\AttributeFamilyRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\AttributeFamily'
        ]);

        Route::get('families/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Attribute\Repositories\AttributeFamilyRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\AttributeFamily'
        ]);


        //Product routes
        Route::get('products', 'ProductController@index');

        Route::get('products/{id}', 'ProductController@get');


        //Product Review routes
        Route::get('reviews', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\ProductReview'
        ]);

        Route::get('reviews/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource' => 'Webkul\API\Http\Resources\Catalog\ProductReview'
        ]);


        //Channel routes
        Route::get('channels', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\ChannelRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Channel'
        ]);

        Route::get('channels/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\ChannelRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Channel'
        ]);


        //Locale routes
        Route::get('locales', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\LocaleRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Locale'
        ]);

        Route::get('locales/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\LocaleRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Locale'
        ]);


        //Slider routes
        Route::get('sliders', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\SliderRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Slider'
        ]);

        Route::get('sliders/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\SliderRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Slider'
        ]);


        //Currency routes
        Route::get('currencies', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\CurrencyRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Currency'
        ]);

        Route::get('currencies/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Core\Repositories\CurrencyRepository',
            'resource' => 'Webkul\API\Http\Resources\Core\Currency'
        ]);


        //Customer routes
        Route::get('customers', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerRepository',
            'resource' => 'Webkul\API\Http\Resources\Customer\Customer'
        ]);

        Route::get('customers/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerRepository',
            'resource' => 'Webkul\API\Http\Resources\Customer\Customer'
        ]);


        //Customer Address routes
        Route::get('addresses', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerAddressRepository',
            'resource' => 'Webkul\API\Http\Resources\Customer\CustomerAddress'
        ]);

        Route::get('addresses/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerAddressRepository',
            'resource' => 'Webkul\API\Http\Resources\Customer\CustomerAddress'
        ]);


        //Order routes
        Route::get('orders', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\OrderRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Order'
        ]);

        Route::get('orders/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\OrderRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Order'
        ]);


        //Invoice routes
        Route::get('invoices', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\InvoiceRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Invoice'
        ]);

        Route::get('invoices/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\InvoiceRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Invoice'
        ]);


        //Invoice routes
        Route::get('shipments', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\ShipmentRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Shipment'
        ]);

        Route::get('shipments/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\ShipmentRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Shipment'
        ]);


        //Wishlist routes
        Route::get('wishlist', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\WishlistRepository',
            'resource' => 'Webkul\API\Http\Resources\Customer\Wishlist'
        ]);
    });
});