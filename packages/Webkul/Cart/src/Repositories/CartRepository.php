<?php

namespace Webkul\Cart\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Cart\Models\Cart';
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function create(array $data)
    {
        $cart = $this->model->create($data);

        return $cart;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */

    public function update(array $data, $id, $attribute = "id")
    {
        $cart = $this->find($id);

        $cart->update($data);

        return $cart;
    }

    public function getProducts($id) {

        return $this->model->find($id)->with_products;
    }

    /**
     * Method to attach
     * associations
     *
     * @return mixed
    */
    // public function onlyAttach($id, $taxRates) {

    //     foreach($taxRates as $key => $value) {

    //         $this->model->findOrFail($id)->tax_rates()->attach($id, ['tax_category_id' => $id, 'tax_rate_id' => $value]);
    //     }
    // }


    /**
     * Method to detach
     * and attach the
     * associations
     *
     * @return mixed
    */
    // public function syncAndDetach($id, $taxRates) {
    //     $this->model->findOrFail($id)->tax_rates()->detach();

    //     foreach($taxRates as $key => $value) {
    //         $this->model->findOrFail($id)->tax_rates()->attach($id, ['tax_category_id' => $id, 'tax_rate_id' => $value]);
    //     }
    // }
}