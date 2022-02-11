<?php

namespace Webkul\Tax\Http\Controllers;

use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Tax\Repositories\TaxRateRepository;

class TaxCategoryController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Tax category repository instance.
     *
     * @var \Webkul\Tax\Repositories\TaxCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * Tax rate repository instance.
     *
     * @var \Webkul\Tax\Repositories\TaxRateRepository
     */
    protected $taxRateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository  $taxCategoryRepository
     * @param  \Webkul\Tax\Repositories\TaxRateRepository  $taxRateRepository
     * @return void
     */
    public function __construct(
        TaxCategoryRepository $taxCategoryRepository,
        TaxRateRepository $taxRateRepository
    ) {
        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->taxRateRepository = $taxRateRepository;

        $this->_config = request('_config');
    }

    /**
     * Function to show the tax category form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view($this->_config['view'])->with('taxRates', $this->taxRateRepository->all());
    }

    /**
     * Function to create the tax category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = request()->input();

        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code',
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        $taxCategory = $this->taxCategoryRepository->create($data);

        $this->taxCategoryRepository->attachOrDetach($taxCategory, $data['taxrates']);

        session()->flash('success', trans('admin::app.settings.tax-categories.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To show the edit form for the tax category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $taxCategory = $this->taxCategoryRepository->findOrFail($id);

        return view($this->_config['view'], compact('taxCategory'));
    }

    /**
     * To update the tax category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code,' . $id,
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        $data = request()->input();

        $taxCategory = $this->taxCategoryRepository->update($data, $id);

        if (! $taxCategory) {
            session()->flash('error', trans('admin::app.settings.tax-categories.update-error'));

            return redirect()->back();
        }

        $this->taxCategoryRepository->attachOrDetach($taxCategory, $data['taxrates']);

        session()->flash('success', trans('admin::app.settings.tax-categories.update-success'));

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
        $this->taxCategoryRepository->findOrFail($id);

        try {
            $this->taxCategoryRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Tax Category'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Tax Category'])], 500);
    }
}
