<?php

namespace Webkul\Core\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

class SubscribersListRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\SubscribersList';
    }

    /**
     * Delete a slider item and delete the image from the disk or where ever it is
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}