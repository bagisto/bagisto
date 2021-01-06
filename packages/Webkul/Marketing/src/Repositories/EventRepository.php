<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;

class EventRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketing\Contracts\Event';
    }
}