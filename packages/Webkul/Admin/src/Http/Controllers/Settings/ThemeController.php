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
        $themes = $this->themeCustomizationRepository->scopeQuery(function($query) {
            return $query->where('type', request()->input('type'))
                ->orderBy('sort_order', 'asc');
        })->get();

        return new JsonResource($themes);
    }

    /**
     * Store the newly created theme
     *
     * @return void
     */
    public function storeStaticContent()
    {
        $htmlCss = array_map(function ($value) {
            return preg_replace('/\s+/', ' ', $value);
        }, request()->only('css', 'html'));
        
        $this->themeCustomizationRepository->create([
            'type'       => request()->input('type'),
            'name'       => request()->input('name'),
            'sort_order' => request()->input('sort_order'),
            'options'    => $htmlCss,
            'status'     => request()->input('status', 1),
        ]);

        return response()->json([
            'message' => 'Static content created successfully.'
        ], 200);
    }

    /**
     * Update the specified Theme
     *
     * @return void
     */
    public function updateStaticContent($id)
    {
        $htmlCss = array_map(function ($value) {
            return preg_replace('/\s+/', ' ', $value);
        }, request()->only('css', 'html'));
        
        $this->themeCustomizationRepository->update([
            'type'       => request()->input('type'),
            'name'       => request()->input('name'),
            'sort_order' => request()->input('sort_order'),
            'options'    => $htmlCss,
            'status'     => request()->input('status', 1),
        ], $id);

        return response()->json([
            'message' => 'Theme updated successfully',
        ], 200);
    }

    /**
     * Delete theme
     *
     * @param integer $id
     * @return void
     */
    public function destroyStaticContent($id)
    {
        $this->themeCustomizationRepository->delete($id);

        return response()->json([
            'message' => 'Static content deleted successfully.'
        ], 200);
    }


    public function storeProductCarousel()
    {
        $this->themeCustomizationRepository->create([
            'type'       => 'product_carousel',
            'name'       => request()->input('name'),
            'options'    => request()->only('sort', 'limit', 'status'),
            'sort_order' => request()->input('sort_order'),
            'status'     => request()->input('theme_status'),
        ]);

        return response()->json([
            'message' => 'Product Carousel created successfully'
        ], 200);
    }
}
