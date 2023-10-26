<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;

class OrderCommentRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Sales\Contracts\OrderComment';
    }
}
