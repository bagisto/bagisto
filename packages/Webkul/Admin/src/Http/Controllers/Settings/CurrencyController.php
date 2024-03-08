<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\DataGrids\Settings\CurrencyDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Rules\Code;

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

        return view('admin::settings.currencies.index', [
            'currencyPositions' => CurrencyPositionEnum::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code' => ['required', 'min:3', 'max:3', 'unique:currencies,code', new Code],
            'name' => 'required',
        ]);

        $this->currencyRepository->create(request()->only([
            'code',
            'name',
            'symbol',
            'decimal',
            'group_separator',
            'decimal_separator',
            'currency_position',
        ]));

        return new JsonResponse([
            'message' => trans('admin::app.settings.currencies.index.create-success'),
        ]);
    }

    /**
     * Currency details.
     */
    public function edit(int $id): JsonResponse
    {
        $currency = $this->currencyRepository->findOrFail($id);

        return new JsonResponse($currency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $id = request('id');

        $this->validate(request(), [
            'code' => ['required', 'unique:currencies,code,'.$id, new Code],
            'name' => 'required',
        ]);

        $this->currencyRepository->update(request()->only([
            'code',
            'name',
            'symbol',
            'decimal',
            'group_separator',
            'decimal_separator',
            'currency_position',
        ]), $id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.currencies.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->currencyRepository->findOrFail($id);

        if ($this->currencyRepository->count() == 1) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.currencies.index.last-delete-error'),
            ], 400);
        }

        try {
            $this->currencyRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.currencies.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);

            return new JsonResponse([
                'message' => trans('admin::app.settings.currencies.index.delete-failed'),
            ], 500);
        }
    }
}
