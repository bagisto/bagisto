<?php

namespace Webkul\Bulkupload\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Import New Products By Admin Reposotory
 *
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