<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAMessage;

class RMAMessageRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAMessage::class;
    }
}
