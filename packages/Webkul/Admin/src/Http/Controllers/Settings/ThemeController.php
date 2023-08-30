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

        return view('admin::settings.theme.index');
    }

    /**
     * Create a new theme
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::settings.theme.create');
    }

    /**
     * Store theme
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = request()->only(['options', 'type', 'name', 'sort_order', 'status']);

        $this->themeCustomizationRepository->create($data);

        session()->flash('success', 'Theme created successfully');

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

        return view('admin::settings.theme.edit', compact('theme'));
    }

    /**
     * Update the specified resource
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        $data = request()->only(['options', 'type', 'name', 'sort_order', 'status']);

        $this->themeCustomizationRepository->update($data, $id);

        session()->flash('success', 'Theme updated successfully');

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
            'message' => 'Theme deleted successfully',
        ], 200);
    }
}
