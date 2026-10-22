<?php

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

// ============================================================================
// Route Parameters Named After Models
// ============================================================================

it('should pass a route parameter named after a model through as a plain value', function (string $parameter) {
    Route::middleware('web')->get("concord-route-parameter/{{$parameter}}", fn (string $value) => $value);

    get('concord-route-parameter/hello')
        ->assertOk()
        ->assertSeeText('hello');
})->with(['template', 'product', 'category', 'channel', 'order']);
