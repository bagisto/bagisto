<?php

namespace Webkul\Admin\Http\Controllers\Settings\Tax;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Tax\Repositories\TaxRateRepository;
use Webkul\Admin\DataGrids\Settings\TaxCategoryDataGrid;
use Webkul\Admin\Http\Resources\TaxCategoryResource;

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

        return view('admin::settings.taxes.categories.index')->with('taxRates', $this->taxRateRepository->all());
    }

    /**
     * Function to create the tax category.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code',
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        Event::dispatch('tax.category.create.before');

        $data = request()->only([
            'code',
            'name',
            'description',
            'taxrates'
        ]);

        $taxCategory = $this->taxCategoryRepository->create($data);

        $taxCategory->tax_rates()->sync($data['taxrates']);

        Event::dispatch('tax.category.create.after', $taxCategory);

        return new JsonResponse([
            'message' => trans('admin::app.settings.taxes.categories.index.create-success'),
        ]);
    }

    /**
     * Tax Category Details
     *
     * @param int $id
     */
    public function edit($id): TaxCategoryResource
    {
        $taxCategory = $this->taxCategoryRepository->findOrFail($id);

        return new TaxCategoryResource($taxCategory);
    }

    /**
     * To update the tax category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): JsonResponse
    {
        $id = request()->id;

        $this->validate(request(), [
            'code'        => 'required|string|unique:tax_categories,code,' . $id,
            'name'        => 'required|string',
            'description' => 'required|string',
            'taxrates'    => 'array|required',
        ]);

        Event::dispatch('tax.category.update.before', $id);

        $data = request()->only([
            'code',
            'name',
            'description',
            'taxrates'
        ]);

        $taxCategory = $this->taxCategoryRepository->update($data, $id);

        $taxCategory->tax_rates()->sync($data['taxrates']);

        Event::dispatch('tax.category.update.after', $taxCategory);

        return new JsonResponse([
            'message' => trans('admin::app.settings.taxes.categories.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->taxCategoryRepository->findOrFail($id);

        try {
            Event::dispatch('tax.category.delete.before', $id);

            $this->taxCategoryRepository->delete($id);

            Event::dispatch('tax.category.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.taxes.categories.index.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.taxes.categories.index.delete-failed'),
        ], 500);
    }
}
