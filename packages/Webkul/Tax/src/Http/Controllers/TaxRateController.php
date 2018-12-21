<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Tax\Repositories\TaxRateRepository as TaxRate;


/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Tax Rate Repository object
     *
     * @var array
     */
    protected $taxRate;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Tax\Repositories\TaxRateRepository  $taxRate
     * @return void
     */
    public function __construct(TaxRate $taxRate)
    {
        $this->middleware('admin');

        $this->taxRate = $taxRate;

        $this->_config = request('_config');
    }

    /**
     * Display a listing
     * resource for the
     * available tax rates.
     *
     * @return mixed
     */

    public function index() {
        return view($this->_config['view']);
    }

    /**
     * Display a create
     * form for tax rate
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Create the tax rate
     *
     * @return mixed
     */
    public function create() {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier',
            'is_zip' => 'sometimes',
            'zip_code' => 'sometimes|required_without:is_zip',
            'zip_from' => 'nullable|required_with:is_zip',
            'zip_to' => 'nullable|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric'
        ]);

        $data = request()->all();

        if(isset($data['is_zip'])) {
            $data['is_zip'] = 1;
            unset($data['zip_code']);
        }

        Event::fire('tax.tax_rate.create.before');

        $taxRate = $this->taxRate->create($data);

        Event::fire('tax.tax_rate.create.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.tax-rates.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the edit form
     * for the previously
     * created tax rates.
     *
     * @return mixed
     */
    public function edit($id)
    {
        $taxRate = $this->taxRate->find($id);

        return view($this->_config['view'])->with('taxRate', $taxRate);
    }

    /**
     * Edit the previous
     * tax rate
     *
     * @return mixed
     */
    public function update($id)
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier,'.$id,
            'is_zip' => 'sometimes',
            'zip_from' => 'nullable|required_with:is_zip',
            'zip_to' => 'nullable|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric'
        ]);

        Event::fire('tax.tax_rate.update.before', $id);

        $taxRate = $this->taxRate->update(request()->input(), $id);

        Event::fire('tax.tax_rate.update.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.tax-rates.update-success'));

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
        if($this->taxRate->count() == 1) {
            session()->flash('error', trans('admin::app.settings.tax-rates.atleast-one'));
        } else {
            Event::fire('tax.tax_rate.delete.before', $id);

            $this->taxRate->delete($id);

            Event::fire('tax.tax_rate.delete.after', $id);

            session()->flash('success', trans('admin::app.settings.tax-rates.delete'));
        }

        return redirect()->back();
    }
}