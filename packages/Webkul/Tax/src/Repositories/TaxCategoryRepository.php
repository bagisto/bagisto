<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Tax Category Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxCategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Tax\Models\TaxCategory';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $taxCategory = $this->model->create($data);

        return $taxCategory;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     *
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $taxCategory = $this->find($id);

        $taxCategory->update($data);

        return $taxmap;
    }

    /**
     * Method to attach
     * associations
     *
     * @return mixed
    */
    public function onlyAttach($id, $taxRates) {

        foreach($taxRates as $key => $value) {

            $this->model->findOrFail($id)->tax_rates()->attach($id, ['tax_category_id' => $id, 'tax_rate_id' => $value]);
        }
    }


    /**
     * Method to detach
     * and attach the
     * associations
     *
     * @return mixed
    */
    public function syncAndDetach($id, $taxRates) {
        $this->model->findOrFail($id)->tax_rates()->detach();

        foreach($taxRates as $key => $value) {
            $this->model->findOrFail($id)->tax_rates()->attach($id, ['tax_category_id' => $id, 'tax_rate_id' => $value]);
        }
    }

}