<?php

namespace Webkul\CartRule\Helpers;

use Webkul\Checkout\Facades\Cart;

class Validator
{
    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Validate cart rule for condition
     *
     * @param CartRule $rule
     * @param CartItem $item
     * @return boolean
     */
    public function validate($rule, $item)
    {
        if (! $rule->conditions)
            return true;

        $validConditionCount = 0;

        foreach ($rule->conditions as $condition) {
            $attributeValue = $this->getAttributeValue($condition, $item);

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

        return $validConditionCount == count($rule->conditions) ? true : false;
    }

    /**
     * Return value for the attribute
     *
     * @param array    $condition
     * @param CartItem $item
     * @return boolean
     */
    public function getAttributeValue($condition, $item)
    {
        $cart = $item->cart;

        $chunks = explode('|', $condition['attribute']);

        $attributeNameChunks = explode('::', $chunks[1]);

        $attributeCode = $attributeNameChunks[count($attributeNameChunks) - 1];

        $attributeScope = count($attributeNameChunks) == 2 ? $attributeNameChunks[0] : null;

        switch (current($chunks)) {
            case 'cart':
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
                return $item->{$attributeCode};

            case 'product':
                $value = $attributeScope == 'children'
                        ? $item->child->product->{$attributeCode}
                        : $item->product->{$attributeCode};

                if (! in_array($condition['attribute_type'], ['multiselect', 'checkbox']))
                    return $value;

                return $value ? explode(',', $value) : [];
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