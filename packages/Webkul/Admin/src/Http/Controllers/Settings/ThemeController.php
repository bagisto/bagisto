<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
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
        return view('admin::theme.index');
    }

    /**
     * Get Themes
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function getThemes(): JsonResource
    {
        $themes = $this->themeCustomizationRepository->findOneWhere([
            'type' => request()->input('type'),
        ]);

        return new JsonResource($themes);
    }


    /**
     * Store the newly created theme
     *
     * @return void
     */
    public function store()
    {
        $this->themeCustomizationRepository->create([
            'type'       => request()->input('type'),
            'name'       => request()->input('name'),
            'sort_order' => request()->input('sort_order'),
            'options'    => request()->only('css', 'html'),
            'status'     => request()->input('status', 1),
        ]);

        return response()->json([
            'message' => 'Static content created successfully.'
        ]);
    }
}
