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

            $attributeValue = $this->getAttributeValue($condition, $entity);

            if ($rule->condition_type == 1) {
                if (! $this->validateAttribute($condition['operator'], $attributeValue, $condition['value'])) {
                    return false;
                } else {
                    $validConditionCount++;
                }
            } else {
                if ($this->validateAttribute($condition['operator'], $attributeValue, $condition['value']))
                    return true;
            }
        }

        return $validConditionCount == $totalConditionCount ? true : false;
    }

    /**
     * Return value for the attribute
     *
     * @param array                 $condition
     * @param Cart|CartItem|Product $entity
     * @return boolean
     */
    public function getAttributeValue($condition, $entity)
    {
        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        $attributeCode = $attributeNameChunks[count($attributeNameChunks) - 1];

        $attributeScope = count($attributeNameChunks) == 2 ? $attributeNameChunks[0] : null;

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
                    $value = $attributeScope == 'children'
                            ? ($entity->child ? $entity->child->product->categories()->pluck('id')->toArray() : [])
                            : ($entity->product
                                ? $entity->product->categories()->pluck('id')->toArray()
                                : $entity->categories()->pluck('id')->toArray()
                            );
                    
                    return $value;
                } else {
                    $value = $attributeScope == 'children'
                            ? ($entity->child ? $entity->child->product->{$attributeCode} : null)
                            : ($entity->product ? $entity->product->{$attributeCode} : $entity->{$attributeCode});

                    if (! in_array($condition['attribute_type'], ['multiselect', 'checkbox']))
                        return $value;
    
                    return $value ? explode(',', $value) : [];
                }
        }
    }

    /**
     * Validate attribute value for condition
     *
     * @param string $operator
     * @param mixed  $attributeValue
     * @param mixed  $conditionValue
     * @return boolean
     */
    public function validateAttribute($operator, $attributeValue, $conditionValue)
    {
        switch ($operator) {
            case '==': case '!=':
                if (is_array($conditionValue)) {
                    if (! is_array($attributeValue))
                        return false;

                    $result = ! empty(array_intersect($conditionValue, $attributeValue));
                } else {
                    if (is_array($attributeValue)) {
                        $result = count($attributeValue) == 1 && array_shift($attributeValue) == $conditionValue;
                    } else {
                        $result = $attributeValue == $conditionValue;
                    }
                }

                break;

            case '<=': case '>':
                if (! is_scalar($attributeValue))
                    return false;

                $result = $attributeValue <= $conditionValue;

                break;

            case '>=': case '<':
                if (! is_scalar($attributeValue))
                    return false;

                $result = $attributeValue >= $conditionValue;

                break;

            case '{}': case '!{}':
                if (is_scalar($attributeValue) && is_array($conditionValue)) {
                    foreach ($conditionValue as $item) {
                        if (stripos($attributeValue, $item) !== false) {
                            $result = true;

                            break;
                        }
                    }
                } else if (is_array($conditionValue)) {
                    if (! is_array($attributeValue))
                        return false;

                    $result = ! empty(array_intersect($conditionValue, $attributeValue));
                } else {
                    if (is_array($attributeValue)) {
                        $result = in_array($conditionValue, $attributeValue);
                    } else {
                        $result = (strpos($attributeValue, $conditionValue) !== false) ? true : false;
                    }
                }

                break;
        }

        if (in_array($operator, ['!=', '>', '<', '!{}']))
            $result = ! $result;

        return $result;
    }
}