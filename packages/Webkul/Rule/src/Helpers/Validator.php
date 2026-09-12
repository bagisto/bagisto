<?php

namespace Webkul\Rule\Helpers;

use Webkul\CartRule\Contracts\CartRule;
use Webkul\CatalogRule\Contracts\CatalogRule;
use Webkul\Checkout\Contracts\Cart as CheckoutContract;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Checkout\Facades\Cart;
use Webkul\Product\Contracts\Product;

class Validator
{
    /**
     * Whether a rule's conditions hold for a cart, a cart item or a product.
     *
     * @param  CartRule|CatalogRule  $rule
     * @param  CheckoutContract|CartItem|Product  $entity
     * @return bool
     */
    public function validate($rule, $entity)
    {
        if (! $rule->conditions) {
            return true;
        }

        $validConditionCount = $totalConditionCount = 0;

        foreach ($rule->conditions as $condition) {
            if (
                ! $condition['attribute']
                || ! isset($condition['value'])
                || is_null($condition['value'])
            ) {
                continue;
            }

            if (
                $entity instanceof CheckoutContract
                && strpos($condition['attribute'], 'cart|') === false
            ) {
                continue;
            }

            $totalConditionCount++;

            if ($rule->condition_type == '1') {
                if (! $this->validateObject($condition, $entity)) {
                    return false;
                } else {
                    $validConditionCount++;
                }
            } elseif ($rule->condition_type == '2') {
                if ($this->validateObject($condition, $entity)) {
                    return true;
                }
            }
        }

        return $validConditionCount == $totalConditionCount;
    }

    /**
     * Get the value a condition compares, for a cart, a cart item or a product.
     *
     * @param  array  $condition
     * @param  CheckoutContract|CartItem|Product  $entity
     * @return mixed
     */
    public function getAttributeValue($condition, $entity)
    {
        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        $attributeCode = $attributeNameChunks[count($attributeNameChunks) - 1];

        switch (current($chunks)) {
            case 'cart':
                $cart = $entity instanceof CheckoutContract ? $entity : $entity->cart;

                if (in_array($attributeCode, ['postcode', 'state', 'country'])) {
                    if (! $cart->shipping_address) {
                        return;
                    }

                    return $cart->shipping_address->{$attributeCode};
                } elseif ($attributeCode == 'shipping_method') {
                    if (! $cart->shipping_method) {
                        return;
                    }

                    $shippingChunks = explode('_', $cart->shipping_method);

                    return current($shippingChunks);
                } elseif ($attributeCode == 'payment_method') {
                    if (! $cart->payment) {
                        return;
                    }

                    return $cart->payment->method;
                } else {
                    return $cart->{$attributeCode};
                }

            case 'cart_item':
                return $entity->{$attributeCode};

            case 'product':
                $product = $entity->product ?? $entity;

                if ($attributeCode == 'category_ids') {
                    return $this->getCategoryIds($product);
                }

                $value = $this->getProductAttributeValue($product, $attributeCode);

                if (! in_array($condition['attribute_type'], ['multiselect', 'checkbox'])) {
                    return $value;
                }

                return $value ? explode(',', $value) : [];
        }
    }

    /**
     * Whether an attribute value meets a condition.
     *
     * @param  array  $condition
     * @param  mixed  $attributeValue
     * @return bool
     */
    public function validateAttribute($condition, $attributeValue)
    {
        switch ($condition['operator']) {
            case '==': case '!=':
                if (is_array($condition['value'])) {
                    if (! is_array($attributeValue)) {
                        return false;
                    }

                    $result = ! empty(array_intersect($condition['value'], $attributeValue));
                } else {
                    if (is_array($attributeValue)) {
                        $result = count($attributeValue) == 1 && array_shift($attributeValue) == $condition['value'];
                    } else {
                        $result = $attributeValue == $condition['value'];
                    }
                }

                break;

            case '<=': case '>':
                if (! is_scalar($attributeValue)) {
                    return false;
                }

                $result = $attributeValue <= $condition['value'];

                break;

            case '>=': case '<':
                if (! is_scalar($attributeValue)) {
                    return false;
                }

                $result = $attributeValue >= $condition['value'];

                break;

            case '{}': case '!{}':
                if (
                    is_scalar($attributeValue)
                    && is_array($condition['value'])
                ) {
                    foreach ($condition['value'] as $item) {
                        if (stripos($attributeValue, $item) !== false) {
                            $result = true;

                            break;
                        }
                    }
                } elseif (is_array($condition['value'])) {
                    if (! is_array($attributeValue)) {
                        return false;
                    }

                    $result = ! empty(array_intersect($condition['value'], $attributeValue));
                } else {
                    if (is_array($attributeValue)) {
                        $result = self::validateArrayValues($attributeValue, $condition['value']);
                    } else {
                        $result = strpos($attributeValue, $condition['value']) !== false;
                    }
                }

                break;
        }

        if (in_array($condition['operator'], ['!=', '>', '<', '!{}'])) {
            $result = ! $result;
        }

        return $result;
    }

    /**
     * Get the ids of the categories a product is found in: its own and, for a variant, its configurable product's.
     *
     * @param  Product  $product
     */
    protected function getCategoryIds($product): array
    {
        $categoryIds = $product->categories()->pluck('id');

        if ($parent = $this->getConfigurableParent($product)) {
            $categoryIds = $categoryIds->merge($parent->categories()->pluck('id'));
        }

        return $categoryIds->unique()->values()->all();
    }

    /**
     * Get a product's value for an attribute, taken from its configurable product when a variant has none of its own.
     *
     * @param  Product  $product
     */
    protected function getProductAttributeValue($product, string $attributeCode): mixed
    {
        $value = $product->{$attributeCode};

        if (! is_null($value)) {
            return $value;
        }

        return $this->getConfigurableParent($product)?->{$attributeCode};
    }

    /**
     * Get the configurable product a variant belongs to, or null for any other product.
     *
     * @param  Product  $product
     * @return Product|null
     */
    protected function getConfigurableParent($product)
    {
        return $product->parent_id ? $product->parent : null;
    }

    /**
     * Whether any of the items a condition's scope covers meets the condition.
     *
     * @param  array  $condition
     * @param  CheckoutContract|CartItem|Product  $entity
     * @return bool
     */
    private function validateObject($condition, $entity)
    {
        $validated = false;

        foreach ($this->getAllItems($this->getAttributeScope($condition), $entity) as $item) {
            $attributeValue = $this->getAttributeValue($condition, $item);

            if ($validated = $this->validateAttribute($condition, $attributeValue)) {
                break;
            }
        }

        return $validated;
    }

    /**
     * Get the items a condition's scope covers: the item, its children, or both.
     *
     * @param  string|null  $attributeScope
     * @param  CheckoutContract|CartItem|Product  $item
     * @return array
     */
    private function getAllItems($attributeScope, $item)
    {
        if ($attributeScope === 'parent') {
            return [$item];
        } elseif ($attributeScope === 'children') {
            return $item->children ?: [$item];
        } else {
            $items = $item->children ?: [];

            $items[] = $item;
        }

        return $items;
    }

    /**
     * Get the scope a condition's attribute names, parent or children, or null for both.
     *
     * @param  array  $condition
     * @return string|null
     */
    private function getAttributeScope($condition)
    {
        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        return count($attributeNameChunks) == 2 ? $attributeNameChunks[0] : null;
    }

    /**
     * Whether a condition value is in an array, searching the arrays nested in it too.
     */
    private static function validateArrayValues(array $attributeValue, string $conditionValue): bool
    {
        if (in_array($conditionValue, $attributeValue, true) === true) {
            return true;
        }

        foreach ($attributeValue as $subValue) {
            if (! is_array($subValue)) {
                continue;
            }

            if (self::validateArrayValues($subValue, $conditionValue) === true) {
                return true;
            }
        }

        return false;
    }
}
