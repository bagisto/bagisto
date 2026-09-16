<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\Event;

class EventRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Event::class;
    }
}
