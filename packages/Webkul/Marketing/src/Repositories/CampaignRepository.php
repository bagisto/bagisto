<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;

class CampaignRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketing\Contracts\Campaign';
    }
}