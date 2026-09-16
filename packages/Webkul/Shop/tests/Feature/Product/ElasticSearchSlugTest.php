<?php

use Webkul\Core\Facades\ElasticSearch;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Repositories\ProductRepository;

it('should find a product by its exact url key in Elasticsearch, not by a copy whose key contains it', function () {
    $original = (new ProductFaker)->getSimpleProductFactory()->create();

    $copy = (new ProductFaker)->getSimpleProductFactory()->create();

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

    $product = app(ProductRepository::class)
        ->setSearchEngine('elastic')
        ->findBySlug($original->url_key);

    expect($product->id)->toBe($original->id)
        ->and($product->id)->not->toBe($copy->id);
});

it('should find no product in Elasticsearch when no url key matches exactly', function () {
    ElasticSearch::shouldReceive('search')
        ->once()
        ->andReturn([
            'hits' => [
                'total' => ['value' => 0],
                'hits' => [],
            ],
        ]);

    expect(app(ProductRepository::class)->setSearchEngine('elastic')->findBySlug('no-such-product'))->toBeNull();
});
