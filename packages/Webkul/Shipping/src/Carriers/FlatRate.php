<?php

namespace Webkul\Shipping\Carriers;

use Config;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

/**
 * Class Rate.
 *
 */
class FlatRate extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'flatrate';

     /**
     * Contains field details
     *
     * @var string
     */
    protected $fields = [
        [
            'name' => 'title',
            'title' => 'Title',
            'type' => 'text',
            'validation' => 'required',
            'channel_based' => true,
            'locale_based' => true
        ], [
            'name' => 'description',
            'title' => 'Description',
            'type' => 'textarea',
            'channel_based' => true,
            'locale_based' => false
        ], [
            'name' => 'active',
            'title' => 'Status',
            'type' => 'select',
            'options' => [
                [
                    'title' => 'Active',
                    'value' => true
                ], [
                    'title' => 'Inactive',
                    'value' => false
                ]
            ],
            'validation' => 'required'
        ]
    ];

    /**
     * Returns rate for flatrate
     *
     * @return array
     */
    public function calculate()
    {
        if(!$this->isAvailable())
            return false;

        $object = new CartShippingRate;

        $object->carrier = 'flatrate';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'flatrate_flatrate';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = core()->convertPrice($this->getConfigData('default_rate'));
        $object->base_price = $this->getConfigData('default_rate');

        return $object;
    }

    /**
     * Returns Configfields for flatrate
     *
     * @return array
     */
    public function getConfigFields()
    {
        return $this->fields;
    }

     /**
     * Returns fieldDetails for flatrate
     *
     * @return array
     */
    public function getFieldDetails($fieldName)
    {
        foreach ($this->fields as $field) {
            if ($fieldName == $field['name']) {
                return $field;
            }
        }
    }
}