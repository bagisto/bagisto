<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Products Flat Event handler
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductsFlat
{
    /**
     * After the attribute is created
     *
     * @return void
     */
    public function afterAttributeCreated($attribute)
    {
        return true;
        dd('after attribute is created');
    }

    /**
     * After the attribute is updated
     *
     * @return void
     */
    public function afterAttributeUpdated($attribute)
    {
        return true;

        dd('after attribute is updated', $attribute);
    }

    public function afterAttributeDeleted()
    {
        return true;

        dd('after attribute is deleted');
    }
}