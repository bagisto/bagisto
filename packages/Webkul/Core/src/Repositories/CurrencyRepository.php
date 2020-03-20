<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class CurrencyRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\Currency';
    }

    /**
     * Specify Model class name
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id) {
        if ($this->model->count() == 1) {
            return false;
        } else {
            if ($this->model->destroy($id)) {
                return true;
            } else {
                return false;
            }
        }
    }
}