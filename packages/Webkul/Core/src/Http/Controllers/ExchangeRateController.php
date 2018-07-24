<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\ExchangeRateRepository as ExchangeRate;
use Webkul\Core\Repositories\CurrencyRepository as Currency;

/**
 * ExchangeRate controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExchangeRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * ExchangeRateRepository object
     *
     * @var array
     */
    protected $exchangeRate;
    
    /**
     * CurrencyRepository object
     *
     * @var array
     */
    protected $currency;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\ExchangeRateRepository  $exchangeRate
     * @param  Webkul\Core\Repositories\CurrencyRepository      $currency
     * @return void
     */
    public function __construct(ExchangeRate $exchangeRate, Currency $currency)
    {
        $this->exchangeRate = $exchangeRate;

        $this->currency = $currency;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = $this->currency->all();

        return view($this->_config['view'], compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sourceCurrency = request()->get('source_currency');
        $this->validate(request(), [
            'source_currency' => 'required',
            'target_currency' => ['required', function ($attribute, $value, $fail) use ($sourceCurrency) {
                if ($value == $sourceCurrency) {
                    $fail('The :attribute value should be different from source currency.');
                }
            }],
            'ratio' => 'required|numeric'
        ]);

        $this->exchangeRate->create(request()->all());

        session()->flash('success', 'Exchange rate created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currencies = $this->currency->all();

        $exchangeRate = $this->exchangeRate->findOrFail($id);

        return view($this->_config['view'], compact('currencies', 'exchangeRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'source_currency' => 'required',
            'target_currency' => 'required',
            'ratio' => 'required|numeric'
        ]);
        
        $this->exchangeRate->update(request()->all(), $id);

        session()->flash('success', 'Exchange rate updated successfully.');

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
        //
    }
}