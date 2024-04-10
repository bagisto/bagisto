<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\CompareItem;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Shop\Mail\Customer\SubscriptionNotification;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('returns a successful response', function () {
    // Act and Assert.
    get(route('shop.home.index'))
        ->assertOk();
});

it('displays the current currency code and channel code', function () {
    // Act
    $response = get(route('shop.home.index'));

    // Assert
    $response->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($response->content(), core()->getCurrentChannelCode()))
        ->toBeTruthy();

    expect(Str::contains($response->content(), core()->getCurrentCurrencyCode()))
        ->toBeTruthy();
});

it('displays the "Sign In" and "Sign Up" buttons when the customer is not logged in', function () {
    // Act
    $response = get(route('shop.home.index'));

    // Assert
    $response->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.sign-in')))
        ->toBeTruthy();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.sign-up')))
        ->toBeTruthy();
});

it('displays navigation buttons when the customer is logged in', function () {
    // Act
    $this->loginAsCustomer();

    $response = get(route('shop.home.index'));

    // Assert
    $response->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.profile')))
        ->toBeTruthy();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.orders')))
        ->toBeTruthy();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.wishlist')))
        ->toBeTruthy();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.logout')))
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
    // Arrange.
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

    // Act and Assert.
    get(route('shop.search.index', [
        'query' => $query = $product->name,
    ]))
        ->assertOk()
        ->assertSeeText($query)
        ->assertSeeText(trans('shop::app.search.title', ['query' => $query]));
});

it('should fails the validation error when provided wrong email address when subscribe to the shop', function () {
    // Act and Assert.
    postJson(route('shop.subscription.store'))
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should store the subscription of the shop', function () {
    // Act and Assert.
    postJson(route('shop.subscription.store'), [
        'email' => $email = fake()->email(),
    ])
        ->assertRedirect();

    $this->assertModelWise([
        SubscribersList::class => [
            [
                'email'         => $email,
                'is_subscribed' => 1,
            ],
        ],
    ]);
});

it('should store the subscription of the shop and send the mail to the admin', function () {
    // Act and Assert.
    Mail::fake();

    postJson(route('shop.subscription.store'), [
        'email' => $email = fake()->email(),
    ])
        ->assertRedirect();

    $this->assertModelWise([
        SubscribersList::class => [
            [
                'email'         => $email,
                'is_subscribed' => 1,
            ],
        ],
    ]);

    Mail::assertQueued(SubscriptionNotification::class);

    Mail::assertQueuedCount(1);
});

it('should unsubscribe from the shop', function () {
    // Arrange.
    $subscriber = SubscribersList::factory()->create();

    // Act and Assert.
    get(route('shop.subscription.destroy', [
        'token' => $subscriber->token,
    ]))
        ->assertRedirect();

    $this->assertDatabaseMissing('subscribers_list', [
        'id' => $subscriber->id,
    ]);
});

it('should store the products to the compare list', function () {
    // Arrange.
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

    // Act and Assert.
    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertSeeText(trans('shop::app.compare.item-add-success'));
});

it('should fails the validation error when not provided product id when move the compare list item', function () {
    // Act and Assert.
    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should remove product from compare list', function () {
    // Arrange.
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

    // Act and Assert.
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
    // Arrange.
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

    // Act and Assert.
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
