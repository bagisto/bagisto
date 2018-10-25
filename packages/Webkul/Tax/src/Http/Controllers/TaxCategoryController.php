<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Channel as Channel;
use Webkul\Tax\Repositories\TaxCategoryRepository as TaxCategory;
use Webkul\Tax\Repositories\TaxRateRepository as TaxRate;
use Webkul\Tax\Repositories\TaxMapRepository as TaxMap;

/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxCategoryController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * TaxCategoryRepository
     *
     * @var mixed
     */
    protected $taxCategory;

    /**
     * TaxRateRepository
     *
     * @var mixed
     */
    protected $taxRate;

    /**
     * TaxMapRepository
     *
     * @var mixed
     */
    protected $taxMap;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Tax\Repositories\TaxCategoryRepository $taxCategory
     * @param  Webkul\Tax\Repositories\TaxRateRepository     $taxRate
     * @param  Webkul\Tax\Repositories\TaxMapRepository      $taxMap
     * @return void
     */
    public function __construct(
        TaxCategory $taxCategory,
        TaxRate $taxRate,
        TaxMap $taxMap
    )
    {
        $this->middleware('admin');

        $this->taxCategory = $taxCategory;

        $this->taxRate = $taxRate;

        $this->taxMap = $taxMap;

        $this->_config = request('_config');
    }

    /**
     * Function to show
     * the tax category form
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view'])->with('taxRates', $this->taxRate->all());
    }

    /**
     * Function to create
     * the tax category.
     *
     * @return view
     */
    public function create()
    {
        $data = request()->input();

        $this->validate(request(), [
            'code' => 'required|string|unique:tax_categories,id',
            'name' => 'required|string|unique:tax_categories,name',
            'description' => 'required|string'
        ]);

        if($currentTaxCategory = $this->taxCategory->create(request()->input())) {
            $allTaxCategorys = $data['taxrates'];

            $this->taxCategory->onlyAttach($currentTaxCategory->id, $allTaxCategorys);

            session()->flash('success', 'New Tax Category Created');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Cannot create the tax category');
        }

        return view($this->_config['view']);
    }

    /**
     * To show the edit form form the tax category
     *
     * @return view
     */

    public function edit($id)
    {
        $taxCategory = $this->taxCategory->findOrFail($id);

        return view($this->_config['view'], compact('taxCategory'));
    }

    /**
     * To update the tax category
     *
     * @return view
     */

    public function update($id) {
        //return the tax category data with the mapping table data also,
        //allow the user to change the tax rates associated with the
        // category also.
        $this->validate(request(), [
            'channel' => 'required|numeric',
            'code' => 'required|string|unique:tax_categories,id,'.$id,
            'name' => 'required|string|unique:tax_categories,name,'.$id,
            'description' => 'required|string',
            'taxrates' => 'array|required'
        ]);

        $data['channel_id'] = request()->input('channel');

        $data['code'] = request()->input('code');

        $data['name'] = request()->input('name');

        $data['description'] = request()->input('description');

        if($this->taxRate->update($data, $id)) {
            $this->taxCategory->syncAndDetach($id, request()->input('taxrates'));

            session()->flash('success', 'Tax Category is successfully edited.');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Tax Category Cannot be Updated Successfully.');

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->taxCategory->count() == 1) {
            session()->flash('error', 'At least one tax category is required.');
        } else {
            $this->taxCategorye->delete($id);

            session()->flash('success', 'Tax category deleted successfully.');
        }

        return redirect()->back();
    }
}