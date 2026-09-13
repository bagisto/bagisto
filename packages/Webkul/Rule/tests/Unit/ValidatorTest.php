<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\CartRule\Models\CartRule;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Rule\Helpers\Validator;

/**
 * A rule holding the given conditions, which must all match or any of which may match.
 */
function ruleWith(array $conditions, int $conditionType = 1): CartRule
{
    return CartRule::factory()->make([
        'conditions' => $conditions,
        'condition_type' => $conditionType,
    ]);
}

/**
 * A condition comparing the given attribute with the given value.
 */
function condition(string $attribute, string $operator, mixed $value, string $type = 'text'): array
{
    return [
        'attribute' => $attribute,
        'operator' => $operator,
        'value' => $value,
        'attribute_type' => $type,
    ];
}

// ============================================================================
// Operators
// ============================================================================

it('compares an attribute value with a condition', function (string $operator, mixed $attributeValue, mixed $conditionValue, bool $expected) {
    $result = (new Validator)->validateAttribute(condition('product|name', $operator, $conditionValue), $attributeValue);

    expect((bool) $result)->toBe($expected);
})->with([
    'equal values match with ==' => ['==', '10', '10', true],
    'different values do not match with ==' => ['==', '10', '5', false],
    'different values match with !=' => ['!=', '10', '5', true],
    'equal values do not match with !=' => ['!=', '10', '10', false],
    'a larger value matches with >=' => ['>=', 10, 5, true],
    'a smaller value does not match with >=' => ['>=', 4, 5, false],
    'a smaller value matches with <' => ['<', 4, 5, true],
    'an equal value does not match with <' => ['<', 5, 5, false],
    'an equal value matches with <=' => ['<=', 5, 5, true],
    'a larger value does not match with <=' => ['<=', 6, 5, false],
    'a larger value matches with >' => ['>', 6, 5, true],
    'an equal value does not match with >' => ['>', 5, 5, false],
    'a substring matches with {}' => ['{}', 'red running shoe', 'shoe', true],
    'a missing substring does not match with {}' => ['{}', 'red running shoe', 'boot', false],
    'a missing substring matches with !{}' => ['!{}', 'red running shoe', 'boot', true],
    'any of several substrings matches with {}' => ['{}', 'red running shoe', ['boot', 'shoe'], true],
    'lists sharing a value match with ==' => ['==', ['1', '2'], ['2', '3'], true],
    'lists sharing nothing do not match with ==' => ['==', ['1', '2'], ['3'], false],
    'a single item list matches its value with ==' => ['==', ['5'], '5', true],
    'a multi item list does not match a single value with ==' => ['==', ['5', '6'], '5', false],
    'a list holding the value matches with {}' => ['{}', ['1', '2'], '2', true],
    'a nested list holding the value matches with {}' => ['{}', [['1'], ['2']], '2', true],
    'a list missing the value matches with !{}' => ['!{}', ['1', '2'], '3', true],
    'lists sharing a value match with {}' => ['{}', ['1', '2'], ['2'], true],
    'a list can not be ordered with <=' => ['<=', ['1'], 5, false],
    'a list can not be compared with a plain value with ==' => ['==', '5', ['5'], false],
]);

// ============================================================================
// Matching Rules
// ============================================================================

it('holds when every condition matches under match-all', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 150]]);

    $rule = ruleWith([
        condition('product|price', '>=', 100, 'price'),
        condition('product|sku', '==', $product->sku),
    ]);

    expect((new Validator)->validate($rule, $product))->toBeTrue();
});

it('fails as soon as one condition misses under match-all', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 150]]);

    $rule = ruleWith([
        condition('product|price', '>=', 200, 'price'),
        condition('product|sku', '==', $product->sku),
    ]);

    expect((new Validator)->validate($rule, $product))->toBeFalse();
});

it('holds when any condition matches under match-any', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 150]]);

    $rule = ruleWith([
        condition('product|price', '>=', 200, 'price'),
        condition('product|sku', '==', $product->sku),
    ], conditionType: 2);

    expect((new Validator)->validate($rule, $product))->toBeTrue();
});

it('fails when no condition matches under match-any', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 150]]);

    $rule = ruleWith([
        condition('product|price', '>=', 200, 'price'),
        condition('product|sku', '==', 'another-sku'),
    ], conditionType: 2);

    expect((new Validator)->validate($rule, $product))->toBeFalse();
});

it('holds for a rule without conditions', function () {
    $product = $this->createSimpleProduct();

    expect((new Validator)->validate(ruleWith([]), $product))->toBeTrue();
});

it('skips a condition that has no value', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 150]]);

    $rule = ruleWith([
        condition('product|price', '>=', null, 'price'),
    ]);

    expect((new Validator)->validate($rule, $product))->toBeTrue();
});

it('only weighs cart conditions when the entity is a cart', function () {
    $cart = Cart::factory()->create(['sub_total' => 500, 'base_sub_total' => 500]);

    $rule = ruleWith([
        condition('product|price', '>=', 1000, 'price'),
        condition('cart|base_sub_total', '>=', 100, 'price'),
    ]);

    expect((new Validator)->validate($rule, $cart))->toBeTrue();
});

// ============================================================================
// Attribute Values
// ============================================================================

it('reads the payment method and the shipping carrier of a cart', function () {
    $cart = Cart::factory()->create(['shipping_method' => 'flatrate_flatrate']);

    CartPayment::factory()->create([
        'cart_id' => $cart->id,
        'method' => 'moneytransfer',
    ]);

    $validator = new Validator;

    expect($validator->getAttributeValue(condition('cart|payment_method', '==', ''), $cart))->toBe('moneytransfer')
        ->and($validator->getAttributeValue(condition('cart|shipping_method', '==', ''), $cart))->toBe('flatrate')
        ->and($validator->getAttributeValue(condition('cart|postcode', '==', ''), $cart))->toBeNull();
});

it('reads the categories of a variant from its configurable product as well', function () {
    $configurable = $this->createConfigurableProduct([100]);

    $category = Category::factory()->has(CategoryTranslation::factory(), 'translations')->create();

    $configurable->categories()->attach($category->id);

    $variant = $configurable->variants->first();

    $categoryIds = (new Validator)->getAttributeValue(condition('product|category_ids', '{}', [], 'multiselect'), $variant);

    expect($categoryIds)->toContain($category->id);
});

it('falls back to the configurable product for an attribute the variant leaves empty', function () {
    $configurable = $this->createConfigurableProduct([100]);

    ProductAttributeValue::factory()->create([
        'product_id' => $configurable->id,
        'attribute_id' => Attribute::query()->where('code', 'cost')->value('id'),
        'float_value' => 42,
    ]);

    $variant = $configurable->variants->first();

    expect($variant->cost)->toBeNull()
        ->and((float) (new Validator)->getAttributeValue(condition('product|cost', '>=', 1, 'price'), $variant))->toBe(42.0);
});
