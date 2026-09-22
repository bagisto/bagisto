<?php

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomizableOption;
use Webkul\Product\Repositories\ProductCustomizableOptionRepository;

/**
 * Create a simple product carrying a select option with two priced values.
 *
 * @return array{0: Product, 1: ProductCustomizableOption}
 */
function productWithSelectOption(): array
{
    $product = test()->createSimpleProduct();

    $option = $product->customizable_options()->create([
        'type' => 'select',
        'is_required' => 1,
        'sort_order' => 0,
        'label' => 'Size',
    ]);

    $option->customizable_option_prices()->create(['label' => 'Small', 'price' => 1, 'sort_order' => 0]);

    $option->customizable_option_prices()->create(['label' => 'Large', 'price' => 2, 'sort_order' => 1]);

    return [$product, $option];
}

/**
 * The input the product edit page sends for one customizable option.
 */
function customizableOptionInput(string $label, string $type, array $prices, int $sortOrder = 0): array
{
    return [
        app()->getLocale() => ['label' => $label],
        'type' => $type,
        'is_required' => 1,
        'sort_order' => $sortOrder,
        'max_characters' => in_array($type, ['text', 'textarea']) ? 10 : null,
        'prices' => $prices,
    ];
}

// ============================================================================
// Option Types
// ============================================================================

it('should give an option switched to a single price type one new price in place of its values', function (string $priceKey) {
    [$product, $option] = productWithSelectOption();

    app(ProductCustomizableOptionRepository::class)->saveCustomizableOptions([
        'customizable_options' => [
            $option->id => customizableOptionInput('Size', 'text', [$priceKey => ['price' => 4]]),
        ],
    ], $product);

    $option->refresh();

    expect($option->type)->toBe('text')
        ->and($option->customizable_option_prices)->toHaveCount(1)
        ->and($option->customizable_option_prices->first()->price)->toBePrice(4);
})->with([
    'a new price key' => ['price_0'],
    'a key the option has no price under' => ['undefined'],
]);

// ============================================================================
// Saving Options
// ============================================================================

it('should keep the options sent, add the new ones and remove the rest', function () {
    [$product, $kept] = productWithSelectOption();

    $removed = $product->customizable_options()->create([
        'type' => 'text',
        'is_required' => 0,
        'sort_order' => 1,
        'label' => 'Colour',
    ]);

    app(ProductCustomizableOptionRepository::class)->saveCustomizableOptions([
        'customizable_options' => [
            $kept->id => customizableOptionInput('Size', 'select', $kept->customizable_option_prices->mapWithKeys(fn ($price) => [
                $price->id => ['label' => $price->label, 'price' => $price->price, 'sort_order' => $price->sort_order],
            ])->all()),
            'option_2' => customizableOptionInput('Engraving', 'text', ['price_0' => ['price' => 5]], 1),
            'option_4' => customizableOptionInput('Gift Wrap', 'select', [
                'price_0' => ['label' => 'Paper', 'price' => 1, 'sort_order' => 0],
                'price_2' => ['label' => 'Box', 'price' => 3, 'sort_order' => 1],
            ], 2),
        ],
    ], $product);

    $options = $product->customizable_options()->orderBy('sort_order')->get();

    expect($options->pluck('label')->all())->toBe(['Size', 'Engraving', 'Gift Wrap'])
        ->and($options->first()->id)->toBe($kept->id)
        ->and($options->first()->customizable_option_prices->pluck('label')->all())->toBe(['Small', 'Large'])
        ->and($options->last()->customizable_option_prices->pluck('label')->all())->toBe(['Paper', 'Box']);

    $this->assertModelMissing($removed);
});

it('should never update an option or value that belongs to another product', function () {
    [$product] = productWithSelectOption();

    [, $otherOption] = productWithSelectOption();

    app(ProductCustomizableOptionRepository::class)->saveCustomizableOptions([
        'customizable_options' => [
            $otherOption->id => customizableOptionInput('Replaced', 'select', [
                $otherOption->customizable_option_prices->first()->id => ['label' => 'Replaced', 'price' => 99, 'sort_order' => 0],
            ]),
        ],
    ], $product);

    $otherOption->refresh();

    expect($otherOption->label)->toBe('Size')
        ->and($otherOption->customizable_option_prices->pluck('label')->all())->toBe(['Small', 'Large'])
        ->and($product->customizable_options()->get()->pluck('label')->all())->toBe(['Replaced']);
});
