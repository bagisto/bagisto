<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Tax Rate Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Tax\Models\TaxRate';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $taxRate = $this->model->create($data);

        return $taxRate;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $taxRate = $this->find($id);

        $taxRate->update($data);

        return $taxRate;
    }
}