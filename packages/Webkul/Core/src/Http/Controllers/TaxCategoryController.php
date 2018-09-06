<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Channel\Channel as Channel;
use Webkul\Core\Repositories\TaxCategoriesRepository as TaxRule;
use Webkul\Core\Repositories\TaxRatesRepository as TaxRate;
use Webkul\Core\Repositories\TaxMapRepository as TaxMap;

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
     * Contains the current
     * channel.
     *
     * @var string
     */
    protected $currentChannelId;

    /**
     * Tax Rule Repository object
     *
     * @var array
     */
    protected $taxRule;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\TaxCategoriesRepository  $taxRule
     * @return void
     */
    public function __construct(TaxRule $taxRule, TaxRate $taxRate, TaxMap $taxMap)
    {
        $this->middleware('admin');

        $this->currentChannelId = core()->getCurrentChannel()->id;

        $this->taxRule = $taxRule;

        $this->taxRate = $taxRate;

        $this->taxMap = $taxMap;

        $this->_config = request('_config');
    }

    /**
     * Function to show
     * the tax rule form
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view'])->with('taxRates', $this->taxRate->all());
    }

    /**
     * Function to create
     * the tax rule.
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

        if($currentTaxRule = $this->taxRule->create(request()->input())) {
            $allTaxRules = $data['taxrates'];

            $this->taxRule->onlyAttach($currentTaxRule->id, $allTaxRules);

            session()->flash('success', 'New Tax Rule Created');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Cannot create the tax rule');
        }

        return view($this->_config['view']);
    }

    /**
     * To show the edit
     * form form the tax
     * rule
     *
     * @return view
     */

    public function edit($id) {

        $taxRates = core()->withRates($id)->toArray();

        $taxRule = $this->taxRule->findByField('id', $id)->toArray();

        return view($this->_config['view'])->with('data', [$taxRule, $taxRates]);

    }

    /**
     * To update the
     * tax rule
     *
     * @return view
     */

    public function update($id) {
        //return the tax rule data with the mapping table data also,
        //allow the user to change the tax rates associated with the
        // rule also.
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
            $this->taxRule->syncAndDetach($id, request()->input('taxrates'));

            session()->flash('success', 'Tax Category is successfully edited.');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Tax Category Cannot be Updated Successfully.');

            return redirect()->back();
        }
    }

    /**
     * Destroy a tax rule
     *
     * @return mixed
     */

    public function destroy($id) {
        if($this->taxRule()->delete($id)) {
            session()->flash('success', 'The tax rule is successfully deleted');

            return redirect()->back();
        }
    }

}