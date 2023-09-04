<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Admin\DataGrids\Settings\CurrencyDataGrid;

class CurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CurrencyRepository $currencyRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CurrencyDataGrid::class)->toJson();
        }

        return view('admin::settings.currencies.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResource
     */
    public function store(): JsonResource
    {
        $this->validate(request(), [
            'code' => 'required|min:3|max:3|unique:currencies,code',
            'name' => 'required',
        ]);

        $data = request()->only([
            'code',
            'name',
            'symbol',
            'decimal'
        ]);

        $this->currencyRepository->create($data);

        return new JsonResource([
            'message' => trans('admin::app.settings.currencies.index.create-success'),
        ]);
    }

    /**
     * Currency Details
     *
     * @param  int  $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $currency = $this->currencyRepository->findOrFail($id);

        return new JsonResource($currency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResource
     */
    public function update(): JsonResource
    {
        $id = request()->id;

        $this->validate(request(), [
            'code' => ['required', 'unique:currencies,code,' . $id, new \Webkul\Core\Rules\Code],
            'name' => 'required',
        ]);

        $data = request()->only([
            'code',
            'name',
            'symbol',
            'decimal'
        ]);

        $this->currencyRepository->update($data, $id);

        return new JsonResource([
            'message' => trans('admin::app.settings.currencies.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->currencyRepository->findOrFail($id);

        if ($this->currencyRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.currencies.index.last-delete-error')
            ], 400);
        }

        try {
            $this->currencyRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.settings.currencies.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.settings.currencies.index.delete-failed')
        ], 500);
    }
}
