<?php

use Webkul\Core\Rules\CommaSeparatedInteger;
use Webkul\Product\Helpers\Toolbar;

beforeEach(function () {
    $this->toolbar = app(Toolbar::class);

    $this->rule = new CommaSeparatedInteger;
});

// ============================================================================
// Validation Rule
// ============================================================================

it('should accept a comma separated list of integers', function (string $value) {
    expect($this->rule->isCommaSeparatedInteger('products_per_page', $value))->toBeTrue();
})->with(['12,24,36,48', '10, 20', '5']);

it('should reject anything that is not a comma separated list of integers', function (string $value) {
    expect($this->rule->isCommaSeparatedInteger('products_per_page', $value))->toBeFalse();
})->with(['ten, twenty', '12,,24', '', '12.5', '-4', 'abc']);

// ============================================================================
// Toolbar Limits
// ============================================================================

it('should fall back to the default page sizes when none are configured', function () {
    $this->setConfig('catalog.products.storefront.products_per_page', '');

    expect($this->toolbar->getAvailableLimits()->all())->toBe(Toolbar::DEFAULT_LIMITS);
});

it('should fall back rather than fail when the configured value is unusable', function (string $value) {
    $this->setConfig('catalog.products.storefront.products_per_page', $value);

    expect($this->toolbar->getAvailableLimits()->all())->toBe(Toolbar::DEFAULT_LIMITS)
        ->and($this->toolbar->getDefaultLimit())->toBe(12);
})->with(['ten, twenty', '0', ' , ', 'abc']);

it('should keep only the usable page sizes from a partly broken value', function () {
    $this->setConfig('catalog.products.storefront.products_per_page', 'ten, 20, 0, 40');

    expect($this->toolbar->getAvailableLimits()->all())->toBe([20, 40]);
});

it('should read a configured list as whole numbers, spaces and repeats aside', function () {
    $this->setConfig('catalog.products.storefront.products_per_page', ' 10, 20 , 20, 30 ');

    expect($this->toolbar->getAvailableLimits()->all())->toBe([10, 20, 30])
        ->and($this->toolbar->getDefaultLimit())->toBe(10);
});
