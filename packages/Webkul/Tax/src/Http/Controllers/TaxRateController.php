<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Webkul\Admin\DataGrids\TaxRateDataGrid;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Tax\Repositories\TaxRateRepository;

class TaxRateController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Tax rate repository instance.
     *
     * @var \Webkul\Tax\Repositories\TaxRateRepository
     */
    protected $taxRateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Tax\Repositories\TaxRateRepository  $taxRateRepository
     * @return void
     */
    public function __construct(TaxRateRepository $taxRateRepository)
    {
        $this->taxRateRepository = $taxRateRepository;

        $this->_config = request('_config');
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

        return view($this->_config['view']);
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

        return view($this->_config['view']);
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

        $data = request()->all();

        if (isset($data['is_zip'])) {
            $data['is_zip'] = 1;

            unset($data['zip_code']);
        }

        $this->taxRateRepository->create($data);

        session()->flash('success', trans('admin::app.settings.tax-rates.create-success'));

        return redirect()->route($this->_config['redirect']);
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

        return view($this->_config['view'])->with('taxRate', $taxRate);
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

        $this->taxRateRepository->update(request()->input(), $id);

        session()->flash('success', trans('admin::app.settings.tax-rates.update-success'));

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
        $this->taxRateRepository->findOrFail($id);

        try {
            $this->taxRateRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Tax Rate'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Tax Rate'])], 500);
    }

    /**
     * Import function for the upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $valid_extension = ['xlsx', 'csv', 'xls'];

        if (! in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension)) {
            session()->flash('error', trans('admin::app.export.upload-error'));
        } else {
            try {
                $excelData = (new DataGridImport)->toArray(request()->file('file'));

                foreach ($excelData as $data) {
                    foreach ($data as $column => $uploadData) {
                        if (is_null($uploadData['state'])) {
                            $uploadData['state'] = '';
                        }

                        if (! is_null($uploadData['zip_from']) && ! is_null($uploadData['zip_to'])) {
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
                            if ($fail->first('identifier')) {
                                $errorMsg[$column] = $fail->first('identifier');
                            } elseif ($fail->first('tax_rate')) {
                                $errorMsg[$column] = $fail->first('tax_rate');
                            } elseif ($fail->first('country')) {
                                $errorMsg[$column] = $fail->first('country');
                            } elseif ($fail->first('state')) {
                                $errorMsg[$column] = $fail->first('state');
                            } elseif ($fail->first('zip_code')) {
                                $errorMsg[$column] = $fail->first('zip_code');
                            } elseif ($fail->first('zip_from')) {
                                $errorMsg[$column] = $fail->first('zip_from');
                            } elseif ($fail->first('zip_to')) {
                                $errorMsg[$column] = $fail->first('zip_to');
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

                                if (! is_null($uploadData['zip_from']) && ! is_null($uploadData['zip_to'])) {
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

                        session()->flash('success', trans('admin::app.response.upload-success', ['name' => 'Tax Rate']));
                    }
                }
            } catch (\Exception $e) {
                report($e);

                $failure = new Failure(1, 'rows', [0 => trans('admin::app.export.enough-row-error')]);

                session()->flash('error', $failure->errors()[0]);
            }
        }

        return redirect()->route($this->_config['redirect']);
    }
}
