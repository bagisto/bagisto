<?php

use Webkul\CMS\Models\Page;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->useIsolatedPageCache();

    $page = Page::factory()->hasTranslations()->create();

    $page->channels()->attach(core()->getCurrentChannel()->id);

    $this->cmsPath = '/page/'.$page->translations->first()->url_key;
});

it('stores a storefront page for a guest', function () {
    get($this->cmsPath)->assertOk();

    $this->assertPageCached($this->pageRequest($this->cmsPath));
});

it('never stores a storefront page for a signed-in customer', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer');

    get($this->cmsPath)->assertOk();

    $this->assertPageNotCached($this->pageRequest($this->cmsPath, $this->currentScope().$customer->id));

    $this->assertPageNotCached($this->pageRequest($this->cmsPath));
});

it('never serves a signed-in customer a page cached for them before', function () {
    $customer = Customer::factory()->create();

    $this->cachePage($this->cmsPath, $this->currentScope().$customer->id);

    $this->actingAs($customer, 'customer');

    expect(get($this->cmsPath)->assertOk()->getContent())->not->toBe('cached');
});
