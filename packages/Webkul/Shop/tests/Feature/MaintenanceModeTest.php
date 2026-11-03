<?php

use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

/**
 * Put the store into maintenance mode in memory, rather than in the file that the web server and every other test
 * worker read.
 */
function maintenanceModeForThisTestOnly(): void
{
    config(['app.maintenance.driver' => 'array']);

    app()->maintenanceMode()->activate([]);
}

/**
 * Create a channel on its own host with its maintenance mode switched on.
 */
function channelUnderMaintenance(): Channel
{
    return Channel::factory()->create([
        'hostname' => 'http://maintenance.test',
        'is_maintenance_on' => true,
    ]);
}

beforeEach(function () {
    maintenanceModeForThisTestOnly();

    $this->channel = channelUnderMaintenance();

    $this->adminUrl = trim(config('app.admin_url'), '/');

    Core::clearResolvedInstance(BaseCore::class);
});

// ============================================================================
// Storefront
// ============================================================================

it('should hold back the storefront while its channel is under maintenance', function () {
    get($this->channel->hostname)
        ->assertServiceUnavailable();
});

it('should hold back a storefront page whose path only begins with the admin url letters', function () {
    get($this->channel->hostname.'/'.$this->adminUrl.'-desk')
        ->assertServiceUnavailable();
});

// ============================================================================
// Maintenance Payload
// ============================================================================

it('should let a visitor holding the bypass secret through and remember them', function () {
    app()->maintenanceMode()->activate(['secret' => 'let-me-in']);

    get($this->channel->hostname.'/let-me-in')
        ->assertRedirect('/')
        ->assertCookie('laravel_maintenance');
});

it('should send a page request to the redirect the maintenance names', function () {
    app()->maintenanceMode()->activate(['redirect' => '/']);

    get($this->channel->hostname.'/contact-us')
        ->assertRedirect('/');
});

it('should answer a json request with a 503 rather than the redirect or page a browser gets', function (array $payload) {
    app()->maintenanceMode()->activate($payload);

    getJson($this->channel->hostname.'/api/products')
        ->assertServiceUnavailable()
        ->assertHeader('Content-Type', 'application/json');
})->with([
    'redirect' => [['redirect' => '/']],
    'page' => [['template' => '<html>Back soon</html>']],
]);

it('should tell a client when to try again, whether it asked for a page or for json', function (bool $json) {
    config(['app.debug' => false]);

    app()->maintenanceMode()->activate(['retry' => 60]);

    $url = $this->channel->hostname.'/contact-us';

    ($json ? getJson($url) : get($url))
        ->assertServiceUnavailable()
        ->assertHeader('Retry-After', '60');
})->with([
    'page request' => [false],
    'json request' => [true],
]);

// ============================================================================
// Admin Panel
// ============================================================================

it('should keep the admin panel open while the channel is under maintenance', function () {
    get($this->channel->hostname.'/'.$this->adminUrl.'/login')
        ->assertOk();
});

it('should answer a path under the admin url that no admin route matches as not found, rather than a storefront page', function () {
    $category = Category::factory()->has(CategoryTranslation::factory(), 'translations')->create();

    get($this->channel->hostname.'/'.$this->adminUrl.'/'.$category->slug)
        ->assertNotFound();
});
