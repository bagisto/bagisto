<?php

namespace Webkul\Tax\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Tax\Repositories\TaxRateRepository;

/**
 * Tax controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxCategoryController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * TaxCategoryRepository
     *
     * @var Object
     */
    protected $taxCategoryRepository;

    /**
     * TaxRateRepository
     *
     * @var Object
     */
    protected $taxRateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository $taxCategoryRepository
     * @param  \Webkul\Tax\Repositories\TaxRateRepository     $taxRateRepository
     * @return void
     */
    public function __construct(
        TaxCategoryRepository $taxCategoryRepository,
        TaxRateRepository $taxRateRepository
    )
    {
        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->taxRateRepository = $taxRateRepository;

        $this->_config = request('_config');
    }

    /**
     * Function to show the tax category form
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view($this->_config['view'])->with('taxRates', $this->taxRateRepository->all());
    }

    /**
     * Function to create
     * the tax category.
     *
     * @return view
     */
    public function create()
    {
        $data = request()->input();

        $this->validate(request(), [
            'channel_id'  => 'required|numeric',
            'code'        => 'required|string|unique:tax_categories,code',
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required'
        ]);

        Event::dispatch('tax.tax_category.create.before');

        $taxCategory = $this->taxCategoryRepository->create($data);

        //attach the categories in the tax map table
        $this->taxCategoryRepository->attachOrDetach($taxCategory, $data['taxrates']);

        Event::dispatch('tax.tax_category.create.after', $taxCategory);

        session()->flash('success', trans('admin::app.settings.tax-categories.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To show the edit form form the tax category
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $taxCategory = $this->taxCategoryRepository->findOrFail($id);

        return view($this->_config['view'], compact('taxCategory'));
    }

    /**
     * To update the tax category
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'channel_id'  => 'required|numeric',
            'code'        => 'required|string|unique:tax_categories,code,' . $id,
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required'
        ]);

        $data = request()->input();

        Event::dispatch('tax.tax_category.update.before', $id);

        $taxCategory = $this->taxCategoryRepository->update($data, $id);

        Event::dispatch('tax.tax_category.update.after', $taxCategory);

        if (! $taxCategory) {
            session()->flash('error', trans('admin::app.settings.tax-categories.update-error'));

            return redirect()->back();
        }

        $taxRates = $data['taxrates'];

        //attach the categories in the tax map table
        $this->taxCategoryRepository->attachOrDetach($taxCategory, $taxRates);

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
        $taxCategory = $this->taxCategoryRepository->findOrFail($id);

        try {
            Event::dispatch('tax.tax_category.delete.before', $id);

            $this->taxCategoryRepository->delete($id);

            Event::dispatch('tax.tax_category.delete.after', $id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Tax Category']));

            return response()->json(['message' => true], 200);
        } catch(Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Tax Category']));
        }

        return response()->json(['message' => false], 400);
    }
}
