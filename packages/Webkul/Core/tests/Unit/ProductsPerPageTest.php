<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Rules\CommaSeparatedInteger;
use Webkul\Product\Helpers\Toolbar;

/**
 * Store a products per page value for the current channel.
 */
function storePerPage(?string $value): void
{
    CoreConfig::query()
        ->where('code', 'catalog.products.storefront.products_per_page')
        ->delete();

    if (! is_null($value)) {
        CoreConfig::query()->create([
            'code' => 'catalog.products.storefront.products_per_page',
            'value' => $value,
            'channel_code' => core()->getCurrentChannelCode(),
            'locale_code' => null,
        ]);
    }

    core()->getConfigData('catalog.products.storefront.products_per_page');
}

beforeEach(function () {
    $this->toolbar = app(Toolbar::class);

    $this->rule = new CommaSeparatedInteger;
});

afterEach(function () {
    storePerPage(null);
});

it('should accept a comma separated list of integers', function (string $value) {
    expect($this->rule->isCommaSeparatedInteger('products_per_page', $value))->toBeTrue();
})->with(['12,24,36,48', '10, 20', '5']);

it('should reject anything that is not a comma separated list of integers', function (string $value) {
    expect($this->rule->isCommaSeparatedInteger('products_per_page', $value))->toBeFalse();
})->with(['ten, twenty', '12,,24', '', '12.5', '-4', 'abc']);

it('should fall back to the default page sizes when none are configured', function () {
    storePerPage(null);

    expect($this->toolbar->getAvailableLimits()->all())->toBe(Toolbar::DEFAULT_LIMITS);
});

it('should fall back rather than fail when the configured value is unusable', function (string $value) {
    storePerPage($value);

    expect($this->toolbar->getAvailableLimits()->all())->toBe(Toolbar::DEFAULT_LIMITS)
        ->and($this->toolbar->getDefaultLimit())->toBe(12);
})->with(['ten, twenty', '0', ' , ', 'abc']);

it('should keep only the usable page sizes from a partly broken value', function () {
    storePerPage('ten, 20, 0, 40');

    expect($this->toolbar->getAvailableLimits()->all())->toBe([20, 40]);
});

it('should read a configured list as whole numbers, spaces and repeats aside', function () {
    storePerPage(' 10, 20 , 20, 30 ');

    expect($this->toolbar->getAvailableLimits()->all())->toBe([10, 20, 30])
        ->and($this->toolbar->getDefaultLimit())->toBe(10);
});
