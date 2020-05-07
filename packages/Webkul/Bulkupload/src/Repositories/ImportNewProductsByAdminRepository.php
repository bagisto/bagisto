<?php

namespace Webkul\Bulkupload\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Import New Products By Admin Reposotory
 *
 * @author    Prateek Srivastava <prateek.srivastava781@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ImportNewProductsByAdminRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Bulkupload\Contracts\ImportNewProductsByAdmin';
    }
}