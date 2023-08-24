<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Admin\DataGrids\LocalesDataGrid;

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
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function store()
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
            'logo_path'
        ]);

        $this->localeRepository->create($data);

        return new JsonResource([
            'message' => trans('admin::app.settings.locales.index.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $locale = $this->localeRepository->findOrFail($id);

        return $locale;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
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
            'logo_path'
        ]);

        $this->localeRepository->update($data, request()->id);

        session()->flash('success', trans('admin::app.settings.locales.edit.update-success'));

        return redirect()->route('admin.locales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->localeRepository->findOrFail($id);

        if ($this->localeRepository->count() == 1) {
            return response()->json(['message' => trans('admin::app.settings.locales.last-delete-error')], 400);
        }

        try {
            $this->localeRepository->delete($id);

            return response()->json(['message' => trans('admin::app.settings.locales.delete-success')]);
        } catch (\Exception $e) {
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Locale'])], 500);
    }
}
