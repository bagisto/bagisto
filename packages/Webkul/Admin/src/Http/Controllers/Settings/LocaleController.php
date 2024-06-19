<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\DataGrids\Settings\LocalesDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\LocaleRepository;

class LocaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected LocaleRepository $localeRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(LocalesDataGrid::class)->process();
        }

        return view('admin::settings.locales.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code'        => ['required', 'unique:locales,code', new \Webkul\Core\Rules\Code],
            'name'        => 'required',
            'direction'   => 'required|in:ltr,rtl',
            'logo_path'   => 'array',
            'logo_path.*' => 'image|extensions:jpeg,jpg,png,svg,webp',
        ]);

        $this->localeRepository->create(request()->only([
            'code',
            'name',
            'direction',
            'logo_path',
        ]));

        return new JsonResponse([
            'message' => trans('admin::app.settings.locales.index.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $locale = $this->localeRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $locale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $this->validate(request(), [
            'name'        => 'required',
            'direction'   => 'required|in:ltr,rtl',
            'logo_path'   => 'array',
            'logo_path.*' => 'image|extensions:jpeg,jpg,png,svg,webp',
        ]);

        $this->localeRepository->update(request()->only([
            'name',
            'direction',
            'logo_path',
        ]), request()->id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.locales.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $locale = $this->localeRepository->findOrFail($id);

        if ($locale->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.locales.index.last-delete-error'),
            ], 400);
        }

        try {
            $locale->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.locales.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('admin::app.settings.locales.index.delete-failed'),
            ], 500);
        }
    }
}
