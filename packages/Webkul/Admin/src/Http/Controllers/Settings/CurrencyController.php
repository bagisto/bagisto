<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Admin\DataGrids\CurrencyDataGrid;

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
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store()
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
            'message' => trans('admin::app.settings.currencies.create.create-success'),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
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

        session()->flash('success', trans('admin::app.settings.currencies.edit.update-success'));

        return redirect()->route('admin.currencies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->currencyRepository->findOrFail($id);

        if ($this->currencyRepository->count() == 1) {
            return response()->json(['message' => trans('admin::app.settings.currencies.last-delete-error')], 400);
        }

        try {
            $this->currencyRepository->delete($id);

            return response()->json(['message' => trans('admin::app.settings.currencies.delete-success')]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Currency'])], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $this->currencyRepository->delete($value);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash) {
                session()->flash('success', trans('admin::app.settings.currencies.index.datagrid.delete-success', ['resource' => 'currencies']));
            } else {
                session()->flash('info', trans('admin::app.settings.currencies.index.datagrid.partial-action', ['resource' => 'currencies']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.settings.currencies.index.datagrid.method-error'));

            return redirect()->back();
        }
    }
}
