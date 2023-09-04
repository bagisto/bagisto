<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Shop\Repositories\ThemeCustomizationRepository;
use Webkul\Admin\DataGrids\Theme\ThemeDatagrid;

class ThemeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public ThemeCustomizationRepository $themeCustomizationRepository)
    {
    }

    /**
     * Display a listing resource for the available tax rates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ThemeDatagrid::class)->toJson();
        }

        return view('admin::settings.themes.index');
    }

    /**
     * Create a new theme
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::settings.themes.create');
    }

    /**
     * Store theme
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = request()->only(['options', 'type', 'name', 'sort_order', 'status']);

        $theme = $this->themeCustomizationRepository->create($data);

        if ($data['type'] == 'image_carousel') {
            $this->themeCustomizationRepository->uploadImage(request()->all('options'), $theme);
        }

        session()->flash('success', trans('admin::app.settings.themes.create-success'));

        return redirect()->route('admin.theme.index');
    }

    /**
     * Edit the theme
     *
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $theme = $this->themeCustomizationRepository->find($id);

        return view('admin::settings.themes.edit', compact('theme'));
    }

    /**
     * Update the specified resource
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        $data = request()->only(['options', 'type', 'name', 'sort_order']);

        $data['status'] = request()->input('status') == 'on';

        if ($data['type'] == 'image_carousel') {
            unset($data['options']);
        }

        $theme = $this->themeCustomizationRepository->update($data, $id);

        if ($data['type'] == 'image_carousel') {
            $this->themeCustomizationRepository->uploadImage(
                request()->all('options'), 
                $theme,
                request()->input('options_remove')
            );
        }

        session()->flash('success', trans('admin::app.settings.themes.update-success'));

        return redirect()->route('admin.theme.index');
    }

    /**
     * Delete a specified theme
     *
     * @return void
     */
    public function destroy($id)
    {
        $this->themeCustomizationRepository->delete($id);

        return response()->json([
            'message' => trans('admin::app.settings.themes.delete-success'),
        ], 200);
    }
}
