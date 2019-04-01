<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Tax\Repositories\TaxRateRepository as TaxRate;
use Webkul\Admin\Imports\DataGridImport;
use Illuminate\Support\Facades\Validator;
use Excel;
use Maatwebsite\Excel\Validators\Failure;

/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Tax Rate Repository object
     *
     * @var array
     */
    protected $taxRate;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Tax\Repositories\TaxRateRepository  $taxRate
     * @return void
     */
    public function __construct(TaxRate $taxRate)
    {
        $this->taxRate = $taxRate;

        $this->_config = request('_config');
    }

    /**
     * Display a listing
     * resource for the
     * available tax rates.
     *
     * @return mixed
     */

    public function index() {
        return view($this->_config['view']);
    }

    /**
     * Display a create
     * form for tax rate
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Create the tax rate
     *
     * @return mixed
     */
    public function create()
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier',
            'is_zip' => 'sometimes',
            'zip_code' => 'sometimes|required_without:is_zip',
            'zip_from' => 'nullable|required_with:is_zip',
            'zip_to' => 'nullable|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric|min:0.0001'
        ]);

        $data = request()->all();

        if (isset($data['is_zip'])) {
            $data['is_zip'] = 1;
            unset($data['zip_code']);
        }

        Event::fire('tax.tax_rate.create.before');

        $taxRate = $this->taxRate->create($data);

        Event::fire('tax.tax_rate.create.after', $taxRate);

        session()->flash('success', trans('admin::app.settings.tax-rates.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the edit form
     * for the previously
     * created tax rates.
     *
     * @return mixed
     */
    public function edit($id)
    {
        $taxRate = $this->taxRate->find($id);

        return view($this->_config['view'])->with('taxRate', $taxRate);
    }

    /**
     * Edit the previous
     * tax rate
     *
     * @return mixed
     */
    public function update($id)
    {
        $this->validate(request(), [
            'identifier' => 'required|string|unique:tax_rates,identifier,'.$id,
            'is_zip' => 'sometimes',
            'zip_from' => 'nullable|required_with:is_zip',
            'zip_to' => 'nullable|required_with:is_zip,zip_from',
            'state' => 'required|string',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric|min:0.0001'
        ]);

        Event::fire('tax.tax_rate.update.before', $id);

        $taxRate = $this->taxRate->update(request()->input(), $id);

        Event::fire('tax.tax_rate.update.after', $taxRate);

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
        // if ($this->taxRate->count() == 1) {
        //     session()->flash('error', trans('admin::app.settings.tax-rates.atleast-one'));
        // } else {


        //     session()->flash('success', trans('admin::app.settings.tax-rates.delete'));
        // }
        Event::fire('tax.tax_rate.delete.before', $id);

        $this->taxRate->delete($id);

        Event::fire('tax.tax_rate.delete.after', $id);

        return redirect()->back();
    }

    /**
     * import function for the upload
     *
     * @return \Illuminate\Http\Response
     */
    public function import() {

        $valid_extension = ['xlsx', 'csv', 'xls'];

        if (!in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension)) {
            session()->flash('error', trans('admin::app.export.upload-error'));
        } else {
            try {
                $excelData = (new DataGridImport)->toArray(request()->file('file'));

                foreach ($excelData as $data) {
                    foreach ($data as $column => $uploadData) {

                        if (!is_null($uploadData['zip_from']) && !is_null($uploadData['zip_to'])) {
                            $uploadData['is_zip'] = 1;
                        }

                        $validator = Validator::make($uploadData, [
                            'identifier' => 'required|string',
                            'state' => 'required|string',
                            'country' => 'required|string',
                            'tax_rate' => 'required|numeric|min:0.0001',
                            'is_zip' => 'sometimes',
                            'zip_code' => 'sometimes|required_without:is_zip',
                            'zip_from' => 'nullable|required_with:is_zip',
                            'zip_to' => 'nullable|required_with:is_zip,zip_from',
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
                            } else if ($fail->first('tax_rate')) {
                                $errorMsg[$coulmn] = $fail->first('tax_rate');
                            } else if ($fail->first('country')) {
                                $errorMsg[$coulmn] = $fail->first('country');
                            } else if ($fail->first('state')) {
                                $errorMsg[$coulmn] = $fail->first('state');
                            } else if ($fail->first('zip_code')) {
                                $errorMsg[$coulmn] = $fail->first('zip_code');
                            } else if ($fail->first('zip_from')) {
                                $errorMsg[$coulmn] = $fail->first('zip_from');
                            } else if ($fail->first('zip_to')) {
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
                        $taxRate = $this->taxRate->get()->toArray();

                        foreach ($taxRate as $rate) {
                            $rateIdentifier[$rate['id']] = $rate['identifier'];
                        }

                        foreach ($excelData as $data) {
                            foreach ($data as $column => $uploadData) {
                                if (!is_null($uploadData['zip_from']) && !is_null($uploadData['zip_to'])) {
                                    $uploadData['is_zip'] = 1;
                                    $uploadData['zip_code'] = NULL;
                                }

                                if (isset($rateIdentifier)) {
                                    $id = array_search($uploadData['identifier'], $rateIdentifier);
                                    if ($id) {
                                        $this->taxRate->update($uploadData, $id);
                                    } else {
                                        $this->taxRate->create($uploadData);
                                    }
                                } else {
                                    $this->taxRate->create($uploadData);
                                }
                            }
                        }

                        session()->flash('success', trans('admin::app.response.upload-success', ['name' => 'Tax Rate']));
                    }
                }
            } catch (\Exception $e) {
                $failure = new Failure(1, 'rows', [0 => trans('admin::app.export.enough-row-error')]);

                session()->flash('error', $failure->errors()[0]);
            }
        }

        return redirect()->route($this->_config['redirect']);
    }
}
