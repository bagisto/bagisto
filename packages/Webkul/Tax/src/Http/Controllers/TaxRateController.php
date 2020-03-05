<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Tax\Repositories\TaxRateRepository;
use Webkul\Admin\Imports\DataGridImport;
use Illuminate\Support\Facades\Validator;
use Excel;
use Maatwebsite\Excel\Validators\Failure;

class TaxRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * TaxRateRepository object
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
        return view($this->_config['view']);
    }

    /**
     * Display a create form for tax rate
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Create the tax rate
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier',
            'is_zip'     => 'sometimes',
            'zip_code'   => 'sometimes|required_without:is_zip',
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

        Event::dispatch('tax.tax_rate.create.before');

        $taxRate = $this->taxRateRepository->create($data);

        Event::dispatch('tax.tax_rate.create.after', $taxRate);

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
     * Edit the previous tax rate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier,'.$id,
            'is_zip'     => 'sometimes',
            'zip_from'   => 'nullable|required_with:is_zip',
            'zip_to'     => 'nullable|required_with:is_zip,zip_from',
            'country'    => 'required|string',
            'tax_rate'   => 'required|numeric|min:0.0001',
        ]);

        Event::dispatch('tax.tax_rate.update.before', $id);

        $taxRate = $this->taxRateRepository->update(request()->input(), $id);

        Event::dispatch('tax.tax_rate.update.after', $taxRate);

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
        $taxRate = $this->taxRateRepository->findOrFail($id);

        try {
            Event::dispatch('tax.tax_rate.delete.before', $id);

            $this->taxRateRepository->delete($id);

            Event::dispatch('tax.tax_rate.delete.after', $id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Tax Rate']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Tax Rate']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * import function for the upload
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

                        if (! is_null($uploadData['zip_from']) && !is_null($uploadData['zip_to'])) {
                            $uploadData['is_zip'] = 1;
                        }

                        $validator = Validator::make($uploadData, [
                            'identifier' => 'required|string',
                            'state'      => 'required|string',
                            'country'    => 'required|string',
                            'tax_rate'   => 'required|numeric|min:0.0001',
                            'is_zip'     => 'sometimes',
                            'zip_code'   => 'sometimes|required_without:is_zip',
                            'zip_from'   => 'nullable|required_with:is_zip',
                            'zip_to'     => 'nullable|required_with:is_zip,zip_from',
                        ]);

                        if ($validator->fails()) {
                            $failedRules[$column+1] = $validator->errors();
                        }

                        $identiFier[$column+1] = $uploadData['identifier'];
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

                    $finalMsg = implode(" ", $message);

                    session()->flash('error', $finalMsg);
                } else {
                    $errorMsg = [];

                    if (isset($failedRules)) {
                        foreach ($failedRules as $coulmn => $fail) {
                            if ($fail->first('identifier')){
                                $errorMsg[$coulmn] = $fail->first('identifier');
                            } elseif ($fail->first('tax_rate')) {
                                $errorMsg[$coulmn] = $fail->first('tax_rate');
                            } elseif ($fail->first('country')) {
                                $errorMsg[$coulmn] = $fail->first('country');
                            } elseif ($fail->first('state')) {
                                $errorMsg[$coulmn] = $fail->first('state');
                            } elseif ($fail->first('zip_code')) {
                                $errorMsg[$coulmn] = $fail->first('zip_code');
                            } elseif ($fail->first('zip_from')) {
                                $errorMsg[$coulmn] = $fail->first('zip_from');
                            } elseif ($fail->first('zip_to')) {
                                $errorMsg[$coulmn] = $fail->first('zip_to');
                            }
                        }

                        foreach ($errorMsg as $key => $msg) {
                            $msg = str_replace(".", "", $msg);
                            $message[] = $msg. ' at Row '  .$key . '.';
                        }

                        $finalMsg = implode(" ", $message);

                        session()->flash('error', $finalMsg);
                    } else {
                        $taxRate = $this->taxRateRepository->get()->toArray();

                        foreach ($taxRate as $rate) {
                            $rateIdentifier[$rate['id']] = $rate['identifier'];
                        }

                        foreach ($excelData as $data) {
                            foreach ($data as $column => $uploadData) {
                                if (! is_null($uploadData['zip_from']) && ! is_null($uploadData['zip_to'])) {
                                    $uploadData['is_zip'] = 1;
                                    $uploadData['zip_code'] = NULL;
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