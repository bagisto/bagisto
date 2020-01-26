<?php

namespace Webkul\Velocity\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * OrderBrands Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderBrandsRepository extends Repository
{   

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\OrderBrands\Repositories\OrderBrandsRepository $OrderBrands
     * @return void
     */
    public function __construct(
        App $app
        )
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Velocity\Models\OrderBrands';
    }
    
}