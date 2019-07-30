<?php

namespace Webkul\PreOrder\Helpers;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;

class PreOrderDataPurger
{
    /**
     * CompanyRepository object
     */
    protected $company;

    /**
     * AttributeRepository object
     */
    protected $attribute;

    /**
     * AttributeFamilyRepository object
     */
    protected $attributeFamily;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily)
    {
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
    }

    /***
     * Creates attributes for one company at a time
     */
    public function createPreOrderData($id)
    {
        $allowPreorderAttribute = $this->attribute->create([
            "code" => "allow_preorder",
            "type" => "boolean",
            "admin_name" => "Allow Preorder",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $preorderQtyAttribute = $this->attribute->create([
            "code" => "preorder_qty",
            "type" => "text",
            "admin_name" => "Preorder Qty",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "numeric",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $preorderAvailabilityAttribute = $this->attribute->create([
            "code" => "preorder_availability",
            "type" => "date",
            "admin_name" => "Product Availability",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $attributeFamilies = $this->attributeFamily->all();

        foreach ($attributeFamilies as $attributeFamily) {
            $generalGroup = $attributeFamily->attribute_groups()->where(['name' => 'General', 'company_id' => $id])->first();

            $generalGroup->custom_attributes()->save($allowPreorderAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 1 ]);

            $generalGroup->custom_attributes()->save($preorderQtyAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 2 ]);

            $generalGroup->custom_attributes()->save($preorderAvailabilityAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 3 ]);
        }

        return true;
    }
}