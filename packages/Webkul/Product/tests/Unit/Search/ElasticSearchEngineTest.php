<?php

use Webkul\Core\Facades\ElasticSearch;
use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;

// ============================================================================
// Finding By Slug
// ============================================================================

it('should find a product by its exact url key, not by a copy whose key contains it', function () {
    $original = $this->createSimpleProduct();

    $copy = $this->createSimpleProduct([
        'url_key' => [
            'text_value' => $original->url_key.'-copy',
            'locale' => app()->getLocale(),
        ],
    ]);

    ElasticSearch::shouldReceive('search')
        ->once()
        ->withArgs(fn (array $params) => $params['body']['query'] === [
            'term' => ['url_key.keyword' => $original->url_key],
        ])
        ->andReturn([
            'hits' => [
                'total' => ['value' => 1],
                'hits' => [['_id' => (string) $original->id]],
            ],
        ]);

    expect(app(ElasticSearchEngine::class)->findBySlug($original->url_key))
        ->toBe($original->id)
        ->not->toBe($copy->id);
});

it('should find no product when no url key matches exactly', function () {
    ElasticSearch::shouldReceive('search')
        ->once()
        ->andReturn([
            'hits' => [
                'total' => ['value' => 0],
                'hits' => [],
            ],
        ]);

    expect(app(ElasticSearchEngine::class)->findBySlug('no-such-product'))->toBeNull();
});
