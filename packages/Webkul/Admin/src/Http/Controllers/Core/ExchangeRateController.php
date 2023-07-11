<?php

namespace Webkul\Admin\Http\Controllers\Core;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\ExchangeRatesDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;

class ExchangeRateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ExchangeRateRepository $exchangeRateRepository,
        protected CurrencyRepository $currencyRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ExchangeRatesDataGrid::class)->toJson();
        }

        return view('admin::settings.exchange_rates.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $currencies = $this->currencyRepository->with('exchange_rate')->all();

        return view('admin::settings.exchange_rates.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'target_currency' => ['required', 'unique:currency_exchange_rates,target_currency'],
            'rate'            => 'required|numeric',
        ]);

        Event::dispatch('core.exchange_rate.create.before');

        $exchangeRate = $this->exchangeRateRepository->create(request()->all());

        Event::dispatch('core.exchange_rate.create.after', $exchangeRate);

        session()->flash('success', trans('admin::app.settings.exchange_rates.create-success'));

        return redirect()->route('admin.exchange_rates.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $currencies = $this->currencyRepository->all();

        $exchangeRate = $this->exchangeRateRepository->findOrFail($id);

        return view('admin::settings.exchange_rates.edit', compact('currencies', 'exchangeRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'target_currency' => ['required', 'unique:currency_exchange_rates,target_currency,' . $id],
            'rate'            => 'required|numeric',
        ]);

        Event::dispatch('core.exchange_rate.update.before', $id);

        $exchangeRate = $this->exchangeRateRepository->update(request()->all(), $id);

        Event::dispatch('core.exchange_rate.update.after', $exchangeRate);

        session()->flash('success', trans('admin::app.settings.exchange_rates.update-success'));

        return redirect()->route('admin.exchange_rates.index');
    }

    /**
     * Update Rates Using Exchange Rates API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRates()
    {
        try {
            app(config('services.exchange_api.' . config('services.exchange_api.default') . '.class'))->updateRates();

            session()->flash('success', trans('admin::app.settings.exchange_rates.update-success'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->exchangeRateRepository->findOrFail($id);

        try {
            Event::dispatch('core.exchange_rate.delete.before', $id);

            $this->exchangeRateRepository->delete($id);

            Event::dispatch('core.exchange_rate.delete.after', $id);

            return response()->json(['message' => trans('admin::app.settings.exchange_rates.delete-success')]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['message' => trans('admin::app.response.delete-error', ['name' => 'Exchange rate'])], 500);
    }
}
