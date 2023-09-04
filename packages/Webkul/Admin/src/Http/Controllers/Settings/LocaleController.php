<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Admin\DataGrids\Settings\LocalesDataGrid;

class LocaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected LocaleRepository $localeRepository)
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
            return app(LocalesDataGrid::class)->toJson();
        }

        return view('admin::settings.locales.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResource
     */
    public function store(): JsonResource
    {
        $this->validate(request(), [
            'code'      => ['required', 'unique:locales,code', new \Webkul\Core\Rules\Code],
            'name'      => 'required',
            'direction' => 'in:ltr,rtl',
        ]);

        $data = request()->only([
            'code',
            'name',
            'direction',
        ]);
        
        $data['logo_path'] = request()->file('logo_path');

        $this->localeRepository->create($data);

        return new JsonResource([
            'message' => trans('admin::app.settings.locales.index.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $locale = $this->localeRepository->findOrFail($id);

        return new JsonResource([
            'data' => $locale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResource
     */
    public function update(): JsonResource
    {
        $this->validate(request(), [
            'code'      => ['required', 'unique:locales,code,' . request()->id, new \Webkul\Core\Rules\Code],
            'name'      => 'required',
            'direction' => 'in:ltr,rtl',
        ]);

        $data = request()->only([
            'code',
            'name',
            'direction',
        ]);

        $data['logo_path'] = request()->file('logo_path');

        $this->localeRepository->update($data, request()->id);

        return new JsonResource([
            'message' => trans('admin::app.settings.locales.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response|JsonResource
     */
    public function destroy($id)
    {
        $this->localeRepository->findOrFail($id);

        if ($this->localeRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.locales.index.last-delete-error'),
            ], 400);
        }

        try {
            $this->localeRepository->delete($id);

            return new JsonResource([
                'message' => trans('admin::app.settings.locales.index.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.settings.locales.index.delete-failed'),
        ], 500);
    }
}
