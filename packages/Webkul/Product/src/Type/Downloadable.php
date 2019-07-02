<?php

namespace Webkul\Product\Type;

/**
 * Class Downloadable.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Downloadable extends AbstractType
{
    /**
     * Skip attribute for downloadable product type
     *
     * @var array
     */
    protected $skipAttributes = ['width', 'height', 'depth', 'weight'];

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.downloadable',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Return true if this product type is saleable
     *
     * @return array
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;
        
        if ($this->product->downloadable_links()->count())
            return true;

        return false;            
    }

    /**
     * Return true if this product can have inventory
     *
     * @return array
     */
    public function isStockable()
    {
        return false;
    }

    /**
     * Returns validation rules
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            // 'downloadable_links.*.title' => 'required',
            'downloadable_links.*.type' => 'required',
            'downloadable_links.*.file' => 'required_if:type,==,file',
            'downloadable_links.*.file_name' => 'required_if:type,==,file',
            'downloadable_links.*.url' => 'required_if:type,==,url',
            'downloadable_links.*.downloads' => 'required',
            'downloadable_links.*.sort_order' => 'required',
        ];
    }
}