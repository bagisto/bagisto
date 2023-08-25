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
}
