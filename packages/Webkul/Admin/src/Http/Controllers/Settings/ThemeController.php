<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Webkul\Admin\DataGrids\Theme\ThemeDatagrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Shop\Repositories\ThemeCustomizationRepository;

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

        return view('admin::theme.index');
    }

    /**
     * Create a new theme
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::theme.create');
    }

    /**
     * Store theme
     *
     * @return void
     */
    public function store()
    {
        $data = request()->only(['options', 'type', 'name', 'sort_order', 'status']);

        if (request()->input('type') == 'static_content') {
            $data['options'] = request()->only('css', 'html');
        }

        $this->themeCustomizationRepository->create($data);

        session()->flash('success', 'Carousel created successfully');

        return redirect()->back();
    }
}
