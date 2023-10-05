<?php

namespace Webkul\Admin\Http\Controllers\Settings\Tax;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Tax\Repositories\TaxRateRepository;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Admin\DataGrids\Settings\TaxRateDataGrid;

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
            'identifier' => 'required|string|unique:tax_rates,identifier,' . $id,
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
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
            )
        ], 500);
    }

    /**
     * Import function for the upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $valid_extension = ['xlsx', 'csv', 'xls'];

        if (!in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension)) {
            session()->flash('error', trans('admin::app.export.upload-error'));
        } else {
            try {
                $excelData = (new DataGridImport)->toArray(request()->file('file'));

                foreach ($excelData as $data) {
                    foreach ($data as $column => $uploadData) {
                        if (is_null($uploadData['state'])) {
                            $uploadData['state'] = '';
                        }

                        if (
                            !is_null($uploadData['zip_from'])
                            && !is_null($uploadData['zip_to'])
                        ) {
                            $uploadData['is_zip'] = 1;
                        }

                        $validator = Validator::make($uploadData, [
                            'identifier' => 'required|string',
                            'country'    => 'required|string',
                            'tax_rate'   => 'required|numeric|min:0.0001',
                            'is_zip'     => 'sometimes',
                            'zip_code'   => 'nullable',
                            'zip_from'   => 'nullable|required_with:is_zip',
                            'zip_to'     => 'nullable|required_with:is_zip,zip_from',
                        ]);

                        if ($validator->fails()) {
                            $failedRules[$column + 1] = $validator->errors();
                        }

                        $identiFier[$column + 1] = $uploadData['identifier'];
                    }

                    $identiFierCount = array_count_values($identiFier);

                    $filtered = array_filter($identiFier, function ($value) use ($identiFierCount) {
                        return $identiFierCount[$value] > 1;
                    });
                }

                if ($filtered) {
                    foreach ($filtered as $position => $identifier) {
                        $message[] = trans('admin::app.export.duplicate-error', ['identifier' => $identifier, 'position' => $position]);
                    }

                    $finalMsg = implode(' ', $message);

                    session()->flash('error', $finalMsg);
                } else {
                    $errorMsg = [];

                    if (isset($failedRules)) {
                        foreach ($failedRules as $column => $fail) {
                            switch ($column) {
                                case 'identifier':
                                    $errorMsg[$column] = $fail->first('identifier');
                                    break;

                                case 'tax_rate':
                                    $errorMsg[$column] = $fail->first('tax_rate');
                                    break;

                                case 'country':
                                    $errorMsg[$column] = $fail->first('country');
                                    break;

                                case 'state':
                                    $errorMsg[$column] = $fail->first('state');
                                    break;

                                case 'zip_code':
                                    $errorMsg[$column] = $fail->first('zip_code');
                                    break;

                                case 'zip_from':
                                    $errorMsg[$column] = $fail->first('zip_from');
                                    break;

                                case 'zip_to':
                                    $errorMsg[$column] = $fail->first('zip_to');
                                    break;
                            }
                        }

                        foreach ($errorMsg as $key => $msg) {
                            $msg = str_replace('.', '', $msg);
                            $message[] = $msg . ' at Row ' . $key . '.';
                        }

                        $finalMsg = implode(' ', $message);

                        session()->flash('error', $finalMsg);
                    } else {
                        $taxRate = $this->taxRateRepository->get()->toArray();

                        foreach ($taxRate as $rate) {
                            $rateIdentifier[$rate['id']] = $rate['identifier'];
                        }

                        foreach ($excelData as $data) {
                            foreach ($data as $column => $uploadData) {
                                if (is_null($uploadData['state'])) {
                                    $uploadData['state'] = '';
                                }

                                if (
                                    !is_null($uploadData['zip_from'])
                                    && !is_null($uploadData['zip_to'])
                                ) {
                                    $uploadData['is_zip'] = 1;
                                    $uploadData['zip_code'] = null;
                                }

                                if (isset($rateIdentifier)) {
                                    $id = array_search($uploadData['identifier'], $rateIdentifier);

                                    if ($id) {
                                        $this->taxRateRepository->update($uploadData, $id);
                                    } else {
                                        $this->taxRateRepository->create($uploadData);
                                    }
                                } else {
                                    $this->taxRateRepository->create($uploadData);
                                }
                            }
                        }

                        session()->flash('success', trans('admin::app.export.upload-success', ['name' => 'Tax Rate']));
                    }
                }
            } catch (\Exception $e) {
                report($e);

                $failure = new Failure(1, 'rows', [0 => trans('admin::app.export.enough-row-error')]);

                session()->flash('error', $failure->errors()[0]);
            }
        }

        return redirect()->route('admin.settings.taxes.rates.index');
    }
}
