<?php

namespace Webkul\Admin\Http\Controllers\Settings\Tax;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Settings\TaxRateDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\TaxRateRequest;
use Webkul\Tax\Repositories\TaxRateRepository;

class TaxRateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TaxRateRepository $taxRateRepository) {}

    /**
     * Display a listing resource for the available tax rates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(TaxRateDataGrid::class)->process();
        }

        return view('admin::settings.taxes.rates.index');
    }

    /**
     * Display a create form for tax rate.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (request()->ajax()) {
            return datagrid(TaxRateDataGrid::class)->process();
        }

        return view('admin::settings.taxes.rates.create');
    }

    /**
     * Create the tax rate.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TaxRateRequest $request)
    {
        Event::dispatch('tax.rate.create.before');

        $taxRate = $this->taxRateRepository->create($request->only([
            'identifier',
            'country',
            'state',
            'tax_rate',
            'zip_code',
            'is_zip',
            'zip_from',
            'zip_to',
        ]));

        Event::dispatch('tax.rate.create.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.taxes.rates.create-success'));

        return redirect()->route('admin.settings.taxes.rates.index');
    }

    /**
     * Show the edit form for the previously created tax rates.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $taxRate = $this->taxRateRepository->findOrFail($id);

        return view('admin::settings.taxes.rates.edit')->with('taxRate', $taxRate);
    }

    /**
     * Edit the previous tax rate.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TaxRateRequest $request, int $id)
    {
        Event::dispatch('tax.rate.update.before', $id);

        $taxRate = $this->taxRateRepository->update($request->only([
            'identifier',
            'country',
            'state',
            'tax_rate',
            'zip_code',
            'is_zip',
            'zip_from',
            'zip_to',
        ]), $id);

        Event::dispatch('tax.rate.update.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.taxes.rates.update-success'));

        return redirect()->route('admin.settings.taxes.rates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('tax.rate.delete.before', $id);

            $this->taxRateRepository->delete($id);

            Event::dispatch('tax.rate.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.taxes.rates.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.taxes.rates.delete-failed'),
        ], 500);
    }
}
