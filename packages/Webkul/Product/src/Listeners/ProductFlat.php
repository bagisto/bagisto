<?php

namespace Webkul\Admin\Listeners;


/**
 * Products Flat Event handler
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlat {

    /**
     * After the attribute is updated
     *
     * @return void
     */
    public function afterAttributeUpdated()
    {
        dd('after attribute is created');
    }

}