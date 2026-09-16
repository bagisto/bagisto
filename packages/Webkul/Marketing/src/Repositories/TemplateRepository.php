<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\Template;

class TemplateRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Template::class;
    }
}
