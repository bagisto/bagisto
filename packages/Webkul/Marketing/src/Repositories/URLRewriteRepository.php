<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\URLRewrite;

class URLRewriteRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return URLRewrite::class;
    }
}
