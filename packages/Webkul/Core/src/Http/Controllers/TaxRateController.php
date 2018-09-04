<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\TaxRatesRepository as TaxRate;


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
     * @param  Webkul\Core\Repositories\TaxRatesRepository  $taxRate
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
            'is_zip' => 'sometimes|confirmed',
            'zip_from' => 'nullable|numeric|required_with:is_zip',
            'zip_to' => 'nullable|numeric|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric'
        ]);

        if($this->taxRate->create(request()->input())) {
            session()->flash('success', 'Tax Rate Created Successfully');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Cannot Create Tax Rate');

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * Show the edit form
     * for the previously
     * created tax rates.
     *
     * @return mixed
     */

    public function edit($id) {

        $data = collect($this->taxRate->findOneByField('id', $id));

        return view($this->_config['view'])->with('data', $data);

    }

    /**
     * Edit the previous
     * tax rate
     *
     * @return mixed
     */
    public function update($id) {

        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier,'.$id,
            'is_zip' => 'sometimes|confirmed',
            'zip_from' => 'nullable|numeric|required_with:is_zip',
            'zip_to' => 'nullable|numeric|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric'
        ]);

        if($this->taxRate->update(request()->input(), $id)) {
            session()->flash('success', 'Tax Rate Updated Successfully');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Cannot Create Tax Rate');

            return redirect()->back();
        }

        return redirect()->back();
    }
}