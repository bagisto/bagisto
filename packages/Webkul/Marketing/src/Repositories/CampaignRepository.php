<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\Campaign;

class CampaignRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Campaign::class;
    }
}
