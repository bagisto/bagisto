<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\ExchangeRateRepository as ExchangeRate;
use Webkul\Core\Repositories\CurrencyRepository as Currency;

/**
 * ExchangeRate controller
 *
 * @author Jitendra Singh <jitendra@webkul.com>
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
     * @param  \Webkul\Core\Repositories\ExchangeRateRepository  $exchangeRate
     * @param  \Webkul\Core\Repositories\CurrencyRepository      $currency
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
        $currencies = $this->currency->with('CurrencyExchangeRate')->all();

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
        $this->validate(request(), [
            'target_currency' => ['required', 'unique:currency_exchange_rates,target_currency'],
            'rate' => 'required|numeric'
        ]);

        Event::fire('core.exchange_rate.create.before');

        $exchangeRate = $this->exchangeRate->create(request()->all());

        Event::fire('core.exchange_rate.create.after', $exchangeRate);

        session()->flash('success', trans('admin::app.settings.exchange_rates.create-success'));

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
            'target_currency' => ['required', 'unique:currency_exchange_rates,target_currency,' . $id],
            'rate' => 'required|numeric'
        ]);

        Event::fire('core.exchange_rate.update.before', $id);

        $exchangeRate = $this->exchangeRate->update(request()->all(), $id);

        Event::fire('core.exchange_rate.update.after', $exchangeRate);

        session()->flash('success', trans('admin::app.settings.exchange_rates.update-success'));

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
        $exchangeRate = $this->exchangeRate->findOrFail($id);

        if ($this->exchangeRate->count() == 1) {
            session()->flash('error', trans('admin::app.settings.exchange_rates.last-delete-error'));
        } else {
            try {
                Event::fire('core.exchange_rate.delete.before', $id);

                $this->exchangeRate->delete($id);

                session()->flash('success', trans('admin::app.settings.exchange_rates.delete-success'));

                Event::fire('core.exchange_rate.delete.after', $id);

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-error', ['name' => 'Exchange rate']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}