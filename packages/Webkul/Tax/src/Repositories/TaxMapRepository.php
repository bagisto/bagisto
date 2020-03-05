<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

class TaxMapRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Tax\Contracts\TaxMap';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Tax\Contracts\TaxMap
     */
    public function create(array $data)
    {
        $taxMap = $this->model->create($data);

        return $taxMap;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Tax\Contracts\TaxMap
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $taxMap = $this->find($id);

        $taxMap->update($data);

        return $taxMap;
    }
}