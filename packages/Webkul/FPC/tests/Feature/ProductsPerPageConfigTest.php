<?php

use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Product\Helpers\Toolbar;

/**
 * Save the storefront per-page configuration through the real admin save path.
 */
function saveProductsPerPage(string $value): void
{
    app(CoreConfigRepository::class)->create([
        'locale' => null,
        'channel' => core()->getRequestedChannelCode(),
        'catalog' => [
            'products' => [
                'storefront' => [
                    'products_per_page' => $value,
                ],
            ],
        ],
    ]);
}

it('builds the storefront per-page options from a comma-separated configuration without a manual cache clear', function () {
    app(Toolbar::class)->getAvailableLimits();

    saveProductsPerPage('11,22,33,44');

    expect(app(Toolbar::class)->getAvailableLimits()->all())->toBe([11, 22, 33, 44]);
});

it('normalises a messy comma-separated per-page configuration', function () {
    saveProductsPerPage('10, 20 ,30,30,0,abc,40');

    expect(app(Toolbar::class)->getAvailableLimits()->all())->toBe([10, 20, 30, 40]);
});

it('drops cached storefront pages when the per-page configuration is saved', function () {
    $this->useIsolatedPageCache();

    $home = $this->cachePage('/');

    $this->assertPageCached($home);

    saveProductsPerPage('10,20,30,40');

    $this->assertPageNotCached(
        $home,
        'Saving the per-page configuration must drop cached storefront pages so the new options render.'
    );
});

it('does not reuse a stale per-page response across successive configuration changes', function () {
    saveProductsPerPage('10,20,30');

    expect(app(Toolbar::class)->getAvailableLimits()->all())->toBe([10, 20, 30]);

    saveProductsPerPage('15,25,35,45');

    expect(app(Toolbar::class)->getAvailableLimits()->all())->toBe([15, 25, 35, 45]);
});
