<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderComment;

class OrderCommentRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return OrderComment::class;
    }
}
