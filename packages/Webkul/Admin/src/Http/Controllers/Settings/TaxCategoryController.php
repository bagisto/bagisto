<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Tax\Repositories\TaxRateRepository;
use Webkul\Admin\DataGrids\Settings\TaxCategoryDataGrid;

class TaxCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected TaxCategoryRepository $taxCategoryRepository,
        protected TaxRateRepository $taxRateRepository
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
            return app(TaxCategoryDataGrid::class)->toJson();
        }

        return view('admin::tax.tax-categories.index')->with('taxRates', $this->taxRateRepository->all());;
    }

    /**
     * Function to create the tax category.
     *
     * @return \Illuminate\View\View
     */
    public function store()
    {
        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code',
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        Event::dispatch('tax.tax_category.create.before');

        $data = request()->only([
            'code',
            'name',
            'description',
            'taxrates'
        ]);

        $taxCategory = $this->taxCategoryRepository->create($data);

        $taxCategory->tax_rates()->sync($data['taxrates']);

        Event::dispatch('tax.tax_category.create.after', $taxCategory);

        return new JsonResource([
            'message' => trans('admin::app.settings.taxes.tax-categories.index.create.success'),
        ]);
    }

    /**
     * Tax Category Details
     *
     * @param int $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $taxCategory = $this->taxCategoryRepository->findOrFail($id);

        return new JsonResource($taxCategory);
    }

    /**
     * To update the tax category.
     *
     * @return JsonResource
     */
    public function update(): JsonResource
    {
        $id = request()->id;

        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code,' . $id,
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        Event::dispatch('tax.tax_category.update.before', $id);

        $data = request()->only([
            'code',
            'name',
            'description',
            'taxrates'
        ]);

        $taxCategory = $this->taxCategoryRepository->update($data, $id);

        $taxCategory->tax_rates()->sync($data['taxrates']);

        Event::dispatch('tax.tax_category.update.after', $taxCategory);

        session()->flash('success', trans('admin::app.settings.taxes.tax-categories.edit.update-success'));

        return new JsonResource([
            'message' => trans('admin::app.settings.taxes.tax-categories.index.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResource
     */
    public function destroy($id): JsonResource
    {
        $this->taxCategoryRepository->findOrFail($id);

        try {
            Event::dispatch('tax.tax_category.delete.before', $id);

            $this->taxCategoryRepository->delete($id);

            Event::dispatch('tax.tax_category.delete.after', $id);

            return new JsonResource([
                'message' => trans('admin::app.settings.taxes.tax-categories.edit.delete-success', ['name' => trans('admin::app.settings.taxes.tax-categories.index.tax-category')]),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResource([
            'message' => trans('admin::app.settings.taxes.tax-categories.edit.delete-failed', ['name' => trans('admin::app.settings.taxes.tax-categories.index.tax-category')]),
        ], 500);
    }
}
