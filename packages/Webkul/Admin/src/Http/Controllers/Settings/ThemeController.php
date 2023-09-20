<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
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
     * Store a newly created resource in storage.
     * 
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store()
    {
        if (request()->has('id')) {
            $theme = $this->themeCustomizationRepository->find(request()->input('id'));

            return $this->themeCustomizationRepository->uploadImage(request()->all(), $theme);
        }

        $this->validate(request(), [
            'name'       => 'required',
            'sort_order' => 'required|numeric',
            'type'       => 'in:product_carousel,category_carousel,static_content,image_carousel,footer_links',
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck("id")->toArray())),
        ]);

        Event::dispatch('theme_customization.create.before');

        $theme = $this->themeCustomizationRepository->create([
            'name'       => request()->input('name'),
            'sort_order' => request()->input('sort_order'),
            'type'       => request()->input('type'),
            'channel_id' => request()->input('channel_id'),
        ]);

        Event::dispatch('theme_customization.create.after', $theme);

        return new JsonResponse([
            'redirect_url' => route('admin.settings.themes.edit', $theme->id),
        ]);
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
        $locale = core()->getRequestedLocaleCode();

        $data = request()->all();

        if ($data['type'] == 'static_content') {
            $data[$locale]['options']['html'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data[$locale]['options']['html']); 
            $data[$locale]['options']['css'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data[$locale]['options']['css']); 
        }

        $data['status'] = request()->input('status') == 'on';

        if ($data['type'] == 'image_carousel') {
            unset($data['options']);
        }

        Event::dispatch('theme_customization.update.before', $id);

        $theme = $this->themeCustomizationRepository->update($data, $id);

        if ($data['type'] == 'image_carousel') {
            $this->themeCustomizationRepository->uploadImage(
                request()->all('options'), 
                $theme,
                request()->input('deleted_sliders', [])
            );
        }

        Event::dispatch('theme_customization.update.after', $theme);

        session()->flash('success', trans('admin::app.settings.themes.update-success'));

        return redirect()->route('admin.settings.themes.index');
    }

    /**
     * Delete a specified theme.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Event::dispatch('theme_customization.delete.before', $id);

        $theme = $this->themeCustomizationRepository->find($id);

        $theme?->delete();

        Storage::deleteDirectory('theme/'. $theme->id);

        Event::dispatch('theme_customization.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.themes.delete-success'),
        ], 200);
    }
}
