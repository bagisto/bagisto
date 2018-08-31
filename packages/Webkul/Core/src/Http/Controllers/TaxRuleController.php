<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Channel\Channel as Channel;
use Webkul\Core\Repositories\TaxRuleRepository as TaxRule;
use Webkul\Core\Repositories\TaxRateRepository as TaxRate;
use Webkul\Core\Repositories\TaxMapRepository as TaxMap;


/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRuleController extends Controller
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
     * Tax Map Repository object
     *
     * @var array
     */
    protected $taxMap;


    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\TaxRuleRepository  $taxRule
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
            'code' => 'required|string|unique:tax_rules,id',
            'name' => 'required|string|unique:tax_rules,name',
            'description' => 'required|string'
        ]);

        $data['channel_id'] = $this->currentChannelId;
        unset($data['_token']);

        if($currentTaxRule = $this->taxRule->create($data)) {
            $allTaxRules = $data['taxrates'];

            foreach ($allTaxRules as $index => $taxRuleId) {
                $taxMap['tax_rule'] = $currentTaxRule->id;

                $taxMap['tax_rate'] = $taxRuleId;

                if ($this->taxMap->create($taxMap)) {
                    continue;
                } else {
                    return redirect()->action('TaxRuleController@performRollback',[
                        'taxRuleId' => $taxRuleId
                    ]);
                }
            }

            session()->flash('success', 'New Tax Rule Created');

            return redirect()->route('admin.tax.index');
        } else {
            session()->flash('error', 'Cannot create the tax rule');
        }

        return view($this->_config['view']);
    }
    /**
     * To perform the rollback
     * if in anycase the taxMap
     * records creates any problem.
     *
     * @return mixed
     */
    public function performRollback($taxRuleId) {

        if($this->taxRule->delete($taxRuleId)) {
            Session()->flash('error', 'Cannot Create Tax Rule');

            return redirect()->route('admin.tax.index');
        }
    }

}