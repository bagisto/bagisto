<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
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
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository $taxCategory
     * @param  \Webkul\Tax\Repositories\TaxRateRepository     $taxRate
     * @param  \Webkul\Tax\Repositories\TaxMapRepository      $taxMap
     * @return void
     */
    public function __construct(
        TaxCategory $taxCategory,
        TaxRate $taxRate,
        TaxMap $taxMap
    )
    {
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
            'channel_id' => 'required|numeric',
            'code' => 'required|string|unique:tax_categories,id',
            'name' => 'required|string|unique:tax_categories,name',
            'description' => 'required|string',
            'taxrates' => 'array|required'
        ]);

        Event::fire('tax.tax_category.create.before');

        $taxCategory = $this->taxCategory->create($data);

        //attach the categories in the tax map table
        $this->taxCategory->attachOrDetach($taxCategory, $data['taxrates']);

        Event::fire('tax.tax_category.create.after', $taxCategory);

        session()->flash('success', trans('admin::app.settings.tax-categories.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To show the edit form form the tax category
     *
     * @param int $id
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
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'channel_id' => 'required|numeric',
            'code' => 'required|string|unique:tax_categories,code,'.$id,
            'name' => 'required|string|unique:tax_categories,name,'.$id,
            'description' => 'required|string',
            'taxrates' => 'array|required'
        ]);

        $data = request()->input();

        Event::fire('tax.tax_category.update.before', $id);

        $taxCategory = $this->taxCategory->update($data, $id);

        Event::fire('tax.tax_category.update.after', $taxCategory);

        if (! $taxCategory) {
            session()->flash('error', trans('admin::app.settings.tax-categories.update-error'));

            return redirect()->back();
        }

        $taxRates = $data['taxrates'];

        //attach the categories in the tax map table
        $this->taxCategory->attachOrDetach($taxCategory, $taxRates);

        session()->flash('success', trans('admin::app.settings.tax-categories.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::fire('tax.tax_category.delete.before', $id);

            $this->taxCategory->delete($id);

            Event::fire('tax.tax_category.delete.after', $id);
        } catch(Exception $e) {
            return redirect()->back();
        }

        return redirect()->back();
    }
}
