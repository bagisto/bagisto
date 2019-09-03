<?php

namespace Webkul\Webfont\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Webfont Repository
 *
 * @author  Prashant Singh<prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class WebfontRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Webfont\Contracts\Webfont';
    }
}