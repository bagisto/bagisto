<?php

namespace Webkul\Communication\Repositories;

use Webkul\Core\Eloquent\Repository;

class NewsletterTemplateRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    function model()
    {
        return \Webkul\Communication\Contracts\NewsletterTemplate::class;
    }
}