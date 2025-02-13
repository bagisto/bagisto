<?php

namespace Webkul\GDPR\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\GDPR\Contracts\GDPR;

class GDPRRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GDPR::class;
    }
}
