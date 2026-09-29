<?php

use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\get;

it('renders the product description inside a rich-content wrapper so headings, lists and tables get scoped styles', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $description = '<h1>Heading</h1><p>Paragraph.</p><ul><li>Item</li></ul>';

    $product->update([
        'description' => $description,
    ]);

    // Act.
    $response = get(route('shop.product_or_category.index', $product->url_key));

    // Assert.
    $response->assertOk();

    /**
     * Block-level rich text (h1-h6, table, ul, ol, blockquote) cannot live inside a <p>;
     * the description wrapper must therefore be a <div> that carries the rich-content class
     * so the scoped typography rules in app.css apply.
     */
    $content = $response->getContent();

    expect(str_contains($content, '<div class="rich-content'))->toBeTrue()
        ->and(str_contains($content, '<p class="rich-content'))->toBeFalse()
        ->and(str_contains($content, $description))->toBeTrue();
});