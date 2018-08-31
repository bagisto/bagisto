<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Tax Rule Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRuleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Models\TaxRule';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $taxRule = $this->model->create($data);

        return $taxRule;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $taxRule = $this->find($id);

        $taxRule->update($data);

        return $taxmap;
    }
}