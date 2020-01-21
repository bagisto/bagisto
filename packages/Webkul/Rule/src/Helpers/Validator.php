<?php

namespace Webkul\Rule\Helpers;

use Webkul\Checkout\Facades\Cart;

class Validator
{
    /**
     * Validate cart rule for condition
     *
     * @param CartRule|CatalogRule  $rule
     * @param Cart|CartItem|Product $entity
     * @return boolean
     */
    public function validate($rule, $entity)
    {
        if (! $rule->conditions)
            return true;

        $validConditionCount = $totalConditionCount = 0;

        foreach ($rule->conditions as $condition) {
            if (! $condition['attribute'] || ! isset($condition['value']) || is_null($condition['value']) ||  $condition['value'] == '')
                continue;

            if ($entity instanceof \Webkul\Checkout\Contracts\Cart && strpos($condition['attribute'], 'cart|') === false)
                continue;
            
            $totalConditionCount++;

            if ($rule->condition_type == 1) {
                if (! $this->validateObject($condition, $entity)) {
                    return false;
                } else {
                    $validConditionCount++;
                }
            } else {
                if ($this->validateObject($condition, $entity))
                    return true;
            }
        }

        return $validConditionCount == $totalConditionCount ? true : false;
    }

    /**
     * Return value for the attribute
     *
     * @param array            $condition
     * @param CartItem|Product $entity
     * @return boolean
     */
    public function getAttributeValue($condition, $entity)
    {
        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        $attributeCode = $attributeNameChunks[count($attributeNameChunks) - 1];

        switch (current($chunks)) {
            case 'cart':
                $cart = $entity instanceof \Webkul\Checkout\Contracts\Cart ? $entity : $entity->cart;

                if (in_array($attributeCode, ['postcode', 'state', 'country'])) {
                    if (! $cart->shipping_address)
                        return;

                    return $cart->shipping_address->{$attributeCode};
                } else if ($attributeCode == 'shipping_method') {
                    if (! $cart->shipping_method)
                        return;

                    $shippingChunks = explode('_', $cart->shipping_method);

                    return current($shippingChunks);
                } else if ($attributeCode == 'payment_method') {
                    if (! $cart->payment)
                        return;

                    return $cart->payment->method;
                } else {
                    return $cart->{$attributeCode};
                }

            case 'cart_item':
                return $entity->{$attributeCode};

            case 'product':
                if ($attributeCode == 'category_ids') {
                    $value = $entity->product
                                ? $entity->product->categories()->pluck('id')->toArray()
                                : $entity->categories()->pluck('id')->toArray();
                    
                    return $value;
                } else {
                    $value = $entity->product
                                ? $entity->product->{$attributeCode}
                                : $entity->{$attributeCode};

                    if (! in_array($condition['attribute_type'], ['multiselect', 'checkbox']))
                        return $value;
    
                    return $value ? explode(',', $value) : [];
                }
        }
    }

    /**
     * Validate object
     *
     * @param array    $condition
     * @param CartItem $entity
     * @return bool
     */
    private function validateObject($condition, $entity)
    {
        $validated = false;

        foreach ($this->getAllItems($this->getAttributeScope($condition), $entity) as $item) {
            $attributeValue = $this->getAttributeValue($condition, $item);

            if ($validated = $this->validateAttribute($condition, $attributeValue))
                break;
        }

        return $validated;
    }

    /**
     * Return all cart items
     *
     * @param string                $attributeScope
     * @param Cart|CartItem|Product $item
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
     * Validate object
     *
     * @param array $condition
     * @return string
     */
    private function getAttributeScope($condition)
    {
        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        return count($attributeNameChunks) == 2 ? $attributeNameChunks[0] : null;
    }

    /**
     * Validate attribute value for condition
     *
     * @param array $condition
     * @param mixed $attributeValue
     * @return boolean
     */
    public function validateAttribute($condition, $attributeValue)
    {
        switch ($condition['operator']) {
            case '==': case '!=':
                if (is_array($condition['value'])) {
                    if (! is_array($attributeValue))
                        return false;

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
                if (! is_scalar($attributeValue))
                    return false;

                $result = $attributeValue <= $condition['value'];

                break;

            case '>=': case '<':
                if (! is_scalar($attributeValue))
                    return false;

                $result = $attributeValue >= $condition['value'];

                break;

            case '{}': case '!{}':
                if (is_scalar($attributeValue) && is_array($condition['value'])) {
                    foreach ($condition['value'] as $item) {
                        if (stripos($attributeValue, $item) !== false) {
                            $result = true;

                            break;
                        }
                    }
                } else if (is_array($condition['value'])) {
                    if (! is_array($attributeValue))
                        return false;

                    $result = ! empty(array_intersect($condition['value'], $attributeValue));
                } else {
                    if (is_array($attributeValue)) {
                        $result = in_array($condition['value'], $attributeValue);
                    } else {
                        $result = (strpos($attributeValue, $condition['value']) !== false) ? true : false;
                    }
                }

                break;
        }

        if (in_array($condition['operator'], ['!=', '>', '<', '!{}']))
            $result = ! $result;

        return $result;
    }
}