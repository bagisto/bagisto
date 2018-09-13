<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Webkul\Core\Repositories\TaxCategoriesRepository as TaxRule;
use Webkul\Core\Repositories\TaxRatesRepository as TaxRate;
use Webkul\Core\Repositories\TaxMapRepository as TaxMap;


/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ChannelRepository object
     *
     * @var array
     */
    protected $channel;

    /**
     * Tax Rule Repository object
     *
     * @var array
     */
    protected $taxRule;

    /**
     * Tax Rate Repository object
     *
     * @var array
     */
    protected $taxRate;

    /**
     * Tax Map Repository object
     *
     * @var array
     */
    protected $taxMap;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\ChannelRepository  $channel
     * @param  Webkul\Core\Repositories\TaxCategoriesRepository  $taxRule
     * @param  Webkul\Core\Repositories\TaxRatesRepository  $taxRate
     * @param  Webkul\Core\Repositories\TaxMapRepository  $taxMap
     * @return void
     */
    public function __construct(Channel $channel, TaxRule $taxRule, TaxRate $taxRate, TaxMap $taxMap)
    {
        $this->middleware('admin');

        $this->channel = $channel;

        $this->taxRule = $taxRule;

        $this->taxRate = $taxRate;

        $this->taxMap = $taxMap;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }



}