<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Services\Search\SearchEngineManager;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

dataset('product_types', [
    'simple' => ['simple'],
    'virtual' => ['virtual'],
    'configurable' => ['configurable'],
    'downloadable' => ['downloadable'],
    'grouped' => ['grouped'],
    'bundle' => ['bundle'],
    'booking' => ['booking'],
]);

/**
 * Pin the catalog search engine to the database, so a listing assertion does not depend on
 * how soon Elasticsearch refreshes a product it has just indexed.
 */
function useDatabaseSearchEngine(): void
{
    test()->setConfig([
        SearchEngineManager::ENABLED_KEY => '0',
        SearchEngineManager::ENGINE_KEY => SearchEngineEnum::DATABASE->value,
    ]);

    CacheGeneration::bump(CoreConfigRepository::class);
}

/**
 * Answer the admin product grid from Elasticsearch.
 */
function useElasticSearchAdminGrid(): void
{
    test()->setConfig([
        SearchEngineManager::ENABLED_KEY => '1',
        SearchEngineManager::ENGINE_KEY => SearchEngineEnum::ELASTIC->value,
        SearchEngineManager::ADMIN_MODE_KEY => SearchEngineEnum::ELASTIC->value,
    ]);

    CacheGeneration::bump(CoreConfigRepository::class);
}

// ============================================================================
// Index
// ============================================================================

it('should return the product index page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should return product listing via datagrid', function () {
    useDatabaseSearchEngine();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.index', [
        'filters' => ['product_id' => [$product->id]],
    ]), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->assertJsonPath('records.0.product_id', $product->id)
        ->assertJsonPath('records.0.sku', $product->sku);
});

it('should list a product carried by several channels only once', function () {
    useDatabaseSearchEngine();

    $product = $this->createSimpleProduct();

    $channel = Channel::factory()->create();

    CacheGeneration::bump(ChannelRepository::class);

    $product->channels()->sync([core()->getDefaultChannel()->id, $channel->id]);

    Event::dispatch('catalog.product.update.after', $product);

    expect(ProductFlat::query()
        ->where('product_id', $product->id)
        ->where('locale', app()->getLocale())
        ->count())->toBe(2);

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.index', [
        'filters' => ['product_id' => [$product->id]],
    ]), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.product_id', $product->id);
});

it('should list a product once on the channel it is filtered to', function () {
    useDatabaseSearchEngine();

    $product = $this->createSimpleProduct();

    $channel = Channel::factory()->create();

    CacheGeneration::bump(ChannelRepository::class);

    $product->channels()->sync([core()->getDefaultChannel()->id, $channel->id]);

    Event::dispatch('catalog.product.update.after', $product);

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.index', [
        'filters' => [
            'product_id' => [$product->id],
            'channel' => [$channel->code],
        ],
    ]), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->assertJsonPath('records.0.product_id', $product->id)
        ->assertJsonPath('records.0.channel', $channel->code);
});

it('should page the product grid in Elasticsearch, listing each product once in the order it returned', function () {
    Queue::fake();

    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $channel = Channel::factory()->create();

    CacheGeneration::bump(ChannelRepository::class);

    $second->channels()->sync([core()->getDefaultChannel()->id, $channel->id]);

    Event::dispatch('catalog.product.update.after', $second);

    useElasticSearchAdminGrid();

    ElasticSearch::shouldReceive('search')->once()->andReturnUsing(function (array $params) use (&$body, $first, $second) {
        $body = $params['body'];

        return [
            'hits' => ['hits' => [['_id' => (string) $second->id], ['_id' => (string) $first->id]]],
            'aggregations' => ['total' => ['value' => 2]],
        ];
    });

    $this->loginAsAdmin();

    $records = getJson(route('admin.catalog.products.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('meta.total', 2)
        ->json('records');

    expect(array_column($records, 'product_id'))->toBe([$second->id, $first->id])
        ->and($body['collapse'])->toBe(['field' => 'id'])
        ->and($body['aggs']['total']['cardinality']['field'])->toBe('id');
});

it('should deny guest access to the product index page', function () {
    get(route('admin.catalog.products.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store — All Product Types
// ============================================================================

it('should store a [type] product and redirect to edit', function (string $type) {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    $response = postJson(route('admin.catalog.products.store'), [
        'type' => $type,
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])->assertOk();

    if ($type === 'configurable') {
        $response->assertJsonStructure(['data' => ['attributes']]);

        $this->assertDatabaseMissing('products', ['sku' => $sku]);

        return;
    }

    $response->assertJsonStructure(['data' => ['redirect_url']]);

    $this->assertDatabaseHas('products', [
        'sku' => $sku,
        'type' => $type,
    ]);
})->with('product_types');

// ============================================================================
// Store — Validation
// ============================================================================

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku');
});

it('should fail validation when sku already exists', function () {
    $existing = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'simple',
        'attribute_family_id' => 1,
        'sku' => $existing->sku,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku');
});

// ============================================================================
// Store — Events
// ============================================================================

it('should dispatch create events when storing a [type] product', function (string $type) {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type' => $type,
        'attribute_family_id' => 1,
        'sku' => fake()->uuid(),
    ])
        ->assertOk();

    if ($type === 'configurable') {
        Event::assertNotDispatched('catalog.product.create.before');
        Event::assertNotDispatched('catalog.product.create.after');

        return;
    }

    Event::assertDispatched('catalog.product.create.before');
    Event::assertDispatched('catalog.product.create.after');
})->with('product_types');

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'));
});

it('should render column 1 attribute groups before column 2 on the edit page', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeInOrder([
            'flex-1 max-xl:flex-auto',
            'w-90 max-w-full',
        ]);
});

it('should return 404 for a non-existent product edit page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', Product::query()->max('id') + 1))
        ->assertNotFound();
});

// ============================================================================
// Copy
// ============================================================================

it('should copy an existing product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.product-copied'));

    $copiedProduct = Product::query()->latest('id')->first();

    expect($copiedProduct->id)->not->toBe($product->id)
        ->and($copiedProduct->sku)->toStartWith('temporary-sku-');
});

it('should copy the existing product with customizable options', function () {
    $product = $this->createSimpleProduct();

    $customizableOption = $product->customizable_options()->create([
        'type' => 'select',
        'is_required' => 1,
        'sort_order' => 1,
        'label' => 'Test Option Label',
    ]);

    $customizableOption->customizable_option_prices()->create([
        'label' => 'Test Value Label',
        'price' => 10.00,
        'sort_order' => 1,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.product-copied'));

    $copiedProduct = Product::query()->latest('id')->first();

    expect($copiedProduct->customizable_options)->toHaveCount(1);

    $copiedCustomizableOption = $copiedProduct->customizable_options->first();

    expect($copiedCustomizableOption->type)->toBe('select')
        ->and($copiedCustomizableOption->is_required)->toBeTrue()
        ->and($copiedCustomizableOption->label)->toBe('Test Option Label')
        ->and($copiedCustomizableOption->customizable_option_prices)->toHaveCount(1);

    $copiedPrice = $copiedCustomizableOption->customizable_option_prices->first();

    expect($copiedPrice->label)->toBe('Test Value Label')
        ->and($copiedPrice->price)->toEqual(10.00);
});

it('should copy the download links and samples of a downloadable product', function () {
    $product = $this->createDownloadableProduct();

    $product->downloadable_samples()->create([
        'type' => 'url',
        'url' => 'https://example.com/sample.pdf',
        'sort_order' => 1,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))->assertOk();

    $copiedProduct = Product::query()->latest('id')->first();

    expect($copiedProduct->downloadable_links()->count())->toBe($product->downloadable_links()->count())
        ->and($copiedProduct->downloadable_samples()->count())->toBe($product->downloadable_samples()->count())
        ->and($copiedProduct->downloadable_links->pluck('title')->all())->toBe($product->downloadable_links->pluck('title')->all());
});

it('should copy the booking settings of a booking product', function () {
    $product = $this->createSimpleProduct();

    Product::query()->where('id', $product->id)->update(['type' => 'booking']);

    $product->refresh();

    $bookingProduct = BookingProduct::query()->create([
        'type' => 'default',
        'qty' => 5,
        'location' => 'Studio One',
        'show_location' => 1,
        'product_id' => $product->id,
    ]);

    $bookingProduct->default_slot()->create([
        'booking_type' => 'many',
        'duration' => 60,
        'break_time' => 15,
        'slots' => [['day' => 0, 'from' => '09:00', 'to' => '17:00']],
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))->assertOk();

    $copiedBooking = Product::query()->latest('id')->first()->booking_products()->first();

    expect($copiedBooking)->not->toBeNull()
        ->and($copiedBooking->type)->toBe('default')
        ->and($copiedBooking->location)->toBe('Studio One')
        ->and($copiedBooking->default_slot)->not->toBeNull()
        ->and($copiedBooking->default_slot->duration)->toBe(60)
        ->and($copiedBooking->default_slot->slots)->toBe($bookingProduct->default_slot->slots);
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete a product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);

    $this->assertDatabaseMissing('product_flat', ['product_id' => $product->id]);
});

it('should return error when deleting a non-existent product', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', Product::query()->max('id') + 1))
        ->assertServerError()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-failed'));
});

it('should dispatch events when deleting a product', function () {
    Event::fake();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk();

    Event::assertDispatched('catalog.product.delete.before');
    Event::assertDispatched('catalog.product.delete.after');
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete products', function () {
    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    $survivor = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => $products->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    foreach ($products as $product) {
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        $this->assertDatabaseMissing('product_flat', ['product_id' => $product->id]);

        $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $product->id]);
    }

    $this->assertDatabaseHas('products', ['id' => $survivor->id]);
});

it('should skip the products already gone in a mass delete', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => [Product::query()->max('id') + 1, $product->id],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('should fail mass delete validation when indices are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices');
});

// ============================================================================
// Mass Update
// ============================================================================

it('should mass update product status to active', function () {
    $products = collect([
        $this->createSimpleProduct(['status' => ['boolean_value' => false, 'channel' => core()->getDefaultChannelCode()]]),
        $this->createSimpleProduct(['status' => ['boolean_value' => false, 'channel' => core()->getDefaultChannelCode()]]),
    ]);

    foreach ($products as $product) {
        expect((bool) ProductFlat::query()->where('product_id', $product->id)->value('status'))->toBeFalse();
    }

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));

    foreach ($products as $product) {
        expect((bool) $product->fresh()->status)->toBeTrue()
            ->and((bool) ProductFlat::query()->where('product_id', $product->id)->value('status'))->toBeTrue();
    }
});

it('should mass update product status to inactive', function () {
    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    $untouched = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 0,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));

    foreach ($products as $product) {
        expect((bool) $product->fresh()->status)->toBeFalse()
            ->and((bool) ProductFlat::query()->where('product_id', $product->id)->value('status'))->toBeFalse();
    }

    expect((bool) ProductFlat::query()->where('product_id', $untouched->id)->value('status'))->toBeTrue();
});

it('should fail mass update validation when indices or value are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices')
        ->assertJsonValidationErrorFor('value');
});

// ============================================================================
// Search
// ============================================================================

it('should search products by name', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.search', [
        'query' => $product->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.sku', $product->sku);
});

it('should return empty results for empty search query', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.search'))
        ->assertOk()
        ->assertJsonPath('data', []);
});
