<?php

namespace Webkul\Admin\Http\Controllers\Appearance;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Appearance\SectionDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Theme\Repositories\SectionRepository;

class SectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public SectionRepository $sectionRepository) {}

    /**
     * Display a listing resource for the available tax rates.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(SectionDataGrid::class)->process();
        }

        $theme = SectionDataGrid::requestedTheme();

        return view('admin::appearance.sections.index', [
            'scopedTheme' => $theme,
            'scopedThemeName' => $theme ? (config('themes.shop.'.$theme.'.name') ?? $theme) : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|string
     */
    public function store()
    {
        if (request()->has('id')) {
            $this->validate(request(), [
                core()->getRequestedLocaleCode().'.options.*.image' => 'image|extensions:jpeg,jpg,png,svg,webp',
            ]);

            $section = $this->sectionRepository->find(request()->input('id'));

            return $this->sectionRepository->uploadImage(request()->all(), $section);
        }

        $validated = $this->validate(request(), [
            'name' => 'required',
            'sort_order' => 'required|numeric',
            'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content',
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck('id')->toArray())),
            'theme_code' => 'required',
        ]);

        Event::dispatch('section.create.before');

        $section = $this->sectionRepository->create($validated);

        Event::dispatch('section.create.after', $section);

        return new JsonResponse([
            'redirect_url' => route('admin.appearance.sections.edit', $section->id),
        ]);
    }

    /**
     * Edit the section
     *
     * @return View
     */
    public function edit(int $id)
    {
        $section = $this->sectionRepository->find($id);

        return view('admin::appearance.sections.edit', compact('theme'));
    }

    /**
     * Update the specified resource
     *
     * @return RedirectResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'sort_order' => 'required|numeric',
            'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content',
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck('id')->toArray())),
            'theme_code' => 'required',
        ]);

        $locale = request('locale');

        $data = request()->only(
            'locale',
            'type',
            'name',
            'sort_order',
            'channel_id',
            'theme_code',
            'status',
            $locale
        );

        Event::dispatch('section.update.before', $id);

        $data['status'] = request()->input('status') == 'on';

        $section = $this->sectionRepository->update($data, $id);

        Event::dispatch('section.update.after', $section);

        session()->flash('success', trans('admin::app.appearance.sections.update-success'));

        return redirect()->route('admin.appearance.sections.index');
    }

    /**
     * Delete a specified theme.
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        Event::dispatch('section.delete.before', $id);

        $this->sectionRepository->delete($id);

        Storage::deleteDirectory('section/'.$id);

        Event::dispatch('section.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.delete-success'),
        ], 200);
    }

    /**
     * Change the status of the selected sections.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedSectionIds = $massUpdateRequest->input('indices');

        $this->sectionRepository->massUpdateStatus([
            'status' => $massUpdateRequest->input('value'),
        ], $selectedSectionIds);

        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }

    /**
     * Delete the selected sections.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $selectedSectionIds = $massDestroyRequest->input('indices');

        foreach ($selectedSectionIds as $sectionId) {
            $this->sectionRepository->delete($sectionId);
        }

        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }
}
