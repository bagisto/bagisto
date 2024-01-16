<?php

use Illuminate\Support\Str;
use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\CompareItem;
use Webkul\Customer\Models\Customer as CustomerModel;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    CustomerModel::query()->delete();
    CompareItem::query()->delete();
    SubscribersList::query()->delete();
    Product::query()->delete();
});

it('returns a successful response', function () {
    // Act & Assert
    get(route('shop.home.index'))
        ->assertOk();
});

it('displays the current currency code and channel code', function () {
    // Act
    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), core()->getCurrentChannelCode()))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), core()->getCurrentCurrencyCode()))
        ->toBeTruthy();
});

it('displays the "Sign In" and "Sign Up" buttons when the customer is not logged in', function () {
    // Act
    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.sign-in')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.sign-up')))
        ->toBeTruthy();
});

it('displays navigation buttons when the customer is logged in', function () {
    // Act
    $this->loginAsCustomer();

    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.profile')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.orders')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.wishlist')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.logout')))
        ->toBeTruthy();
});

it('should returns the home page of the sore', function () {
    get(route('shop.home.index'))
        ->assertOk()
        ->assertSeeText('The game with our new additions!')
        ->assertSeeText('Our Collections')
        ->assertSeeText('Get Ready for our new Bold Collections!')
        ->assertSeeText('Get UPTO 40% OFF on your 1st order SHOP NOW');
});

it('should returns the search page of the products', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    get(route('shop.search.index', [
        'query' => $query = $product->name,
    ]))
        ->assertOk()
        ->assertSeeText($query)
        ->assertSeeText(trans('shop::app.search.title', ['query' => $query]));
});

it('should store the subscription of the shop', function () {
    // Act and Assert
    postJson(route('shop.subscription.store'), [
        'email' => $email = fake()->email(),
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('subscribers_list', [
        'email'         => $email,
        'is_subscribed' => 1,
    ]);
});

it('should unsubscribe from the shop', function () {
    // Arrange
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert
    get(route('shop.subscription.destroy', [
        'token' => $subscriber->token,
    ]))
        ->assertRedirect();

    $this->assertDatabaseMissing('subscribers_list', [
        'id' => $subscriber->id,
    ]);
});

it('should store the products to the compare list', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertSeeText(trans('shop::app.compare.item-add-success'));
});

it('should remove product from compare list', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer();

    CompareItem::factory()->create([
        'customer_id'  => auth()->guard('customer')->user()->id,
        'product_id'   => $product->id,
    ]);

    deleteJson(route('shop.api.compare.destroy'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.compare.remove-success'));
});

it('should remove all the products from compare list', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->count(5)->create();

    // Act and Assert
    $this->loginAsCustomer();

    foreach ($products as $product) {
        CompareItem::factory()->create([
            'customer_id'  => auth()->guard('customer')->user()->id,
            'product_id'   => $product->id,
        ]);
    }

    deleteJson(route('shop.api.compare.destroy_all'))
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.compare.remove-all-success'));
});
