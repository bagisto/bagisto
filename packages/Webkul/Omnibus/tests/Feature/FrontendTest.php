<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;

use function Pest\Laravel\get;

beforeEach(function () {
    config(['catalog.products.omnibus.is_enabled' => true]);
    $this->repository = app(OmnibusPriceRepository::class);
    $this->channelId = core()->getCurrentChannel()->id;
    $this->currencyCode = core()->getCurrentCurrencyCode();
});

it('renders the omnibus wrapper inside the product view page alongside variant JSON data', function () {
    $configurableProduct = (new ProductFaker([
        'attributes' => [1 => 'color'],
        'attribute_value' => [
            'color' => ['boolean_value' => true],
        ],
    ]))->getConfigurableProductFactory()->create();

    // Mock OmnibusHelper to simulate that the product has valid historical promo logs
    // This allows us to bypass creating massive EAV architecture datasets in the integration environment
    $mockHelper = Mockery::mock(Webkul\Omnibus\Helpers\OmnibusHelper::class);
    $mockHelper->shouldReceive('getOmnibusPriceHtml')
        ->once()
        ->andReturn('<div id="omnibus-manager" data-variants=\'{"' . $configurableProduct->variants->first()->id . '":"Lowest price 30 days prior to the discount: 20.00 PLN"}\' style="display: none;"></div>');
        
    $mockHelper->shouldReceive('getLowestPriceFormatted')
        ->andReturn('Lowest price 30 days prior to the discount: 20.00 PLN');
        
    app()->instance(Webkul\Omnibus\Helpers\OmnibusHelper::class, $mockHelper);

    // Act
    $response = get(route('shop.product_or_category.index', $configurableProduct->url_key));

    // Assert
    $response->assertOk();
    $response->assertSee('id="omnibus-manager"', false);
    $response->assertSee('data-variants', false);
    $response->assertSee('Lowest price 30 days prior', false);
});
