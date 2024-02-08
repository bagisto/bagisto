<?php

namespace Webkul\Admin\Http\Controllers\Settings\Tax;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Settings\TaxRateDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Tax\Repositories\TaxRateRepository;

class TaxRateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TaxRateRepository $taxRateRepository)
    {
    }

    /**
     * Display a listing resource for the available tax rates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(TaxRateDataGrid::class)->toJson();
        }

        return view('admin::settings.taxes.rates.index');
    }

    /**
     * Display a create form for tax rate.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if (request()->ajax()) {
            return app(TaxRateDataGrid::class)->toJson();
        }

        return view('admin::settings.taxes.rates.create');
    }

    /**
     * Create the tax rate.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier',
            'is_zip'     => 'sometimes',
            'zip_code'   => 'nullable',
            'zip_from'   => 'nullable|required_with:is_zip',
            'zip_to'     => 'nullable|required_with:is_zip,zip_from',
            'country'    => 'required|string',
            'tax_rate'   => 'required|numeric|min:0.0001',
        ]);

        $data = request()->only([
            'identifier',
            'country',
            'state',
            'tax_rate',
            'zip_code',
            'is_zip',
            'zip_from',
            'zip_to',
        ]);

        if ($data['is_zip'] ?? false) {
            $data['is_zip'] = 1;
        }

        Event::dispatch('tax.rate.create.before');

        $taxRate = $this->taxRateRepository->create($data);

        Event::dispatch('tax.rate.create.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.taxes.rates.create-success'));

        return redirect()->route('admin.settings.taxes.rates.index');
    }

    /**
     * Show the edit form for the previously created tax rates.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $taxRate = $this->taxRateRepository->findOrFail($id);

        return view('admin::settings.taxes.rates.edit')->with('taxRate', $taxRate);
    }

    /**
     * Edit the previous tax rate.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier,'.$id,
            'is_zip'     => 'sometimes',
            'zip_code'   => 'nullable',
            'zip_from'   => 'nullable|required_with:is_zip',
            'zip_to'     => 'nullable|required_with:is_zip,zip_from',
            'country'    => 'required|string',
            'tax_rate'   => 'required|numeric|min:0.0001',
        ]);

        Event::dispatch('tax.rate.update.before', $id);

        $data = request()->only([
            'identifier',
            'country',
            'state',
            'tax_rate',
            'zip_code',
            'is_zip',
            'zip_from',
            'zip_to',
        ]);

        $taxRate = $this->taxRateRepository->update($data, $id);

        Event::dispatch('tax.rate.update.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.taxes.rates.update-success'));

        return redirect()->route('admin.settings.taxes.rates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        $this->taxRateRepository->findOrFail($id);

        try {
            Event::dispatch('tax.rate.delete.before', $id);

            $this->taxRateRepository->delete($id);

            Event::dispatch('tax.rate.delete.after', $id);

            return new JsonResponse(['message' => trans('admin::app.settings.taxes.rates.delete-success')]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans(
                'admin::app.settings.taxes.rates.delete-failed'
            ),
        ], 500);
    }
}
