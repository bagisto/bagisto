<?php

namespace Webkul\Admin\Http\Controllers\Appearance;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Theme\Contracts\Section;
use Webkul\Theme\Models\Section as SectionModel;
use Webkul\Theme\Repositories\SectionRepository;
use Webkul\Theme\SectionSchema;

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
    public function index(string $code)
    {
        $theme = $this->themeOrFail($code);

        $channel = $this->requestedChannel();

        $sections = $this->editableSections($code, $channel->id);

        return view('admin::appearance.sections.index', [
            'scopedTheme' => $code,
            'scopedThemeName' => $theme['name'] ?? $code,
            'scopedChannel' => $channel,
            'channels' => core()->getAllChannels(),
            'sections' => $sections,
            'typeLabels' => $this->typeLabels(),
            'previewUrl' => route('shop.appearance.preview', ['channel' => $channel->id]),
            'urls' => $this->editorUrls(),
        ]);
    }

    public function store(string $code)
    {
        $validated = $this->validate(request(), [
            'name' => 'required',
            'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content',
        ]);

        /**
         * The editor is already scoped to one channel and theme, so a new section joins
         * the one being edited rather than asking again.
         */
        $this->themeOrFail($code);

        $channel = $this->requestedChannel();

        Event::dispatch('section.create.before');

        $section = $this->sectionRepository->create($validated + [
            'channel_id' => $channel->id,
            'theme_code' => $code,
            'sort_order' => count($this->editableSections($code, $channel->id)) + 1,

            /**
             * The table defaults this off, which would leave a section the operator just
             * created missing from its own preview.
             */
            'status' => 1,
        ]);

        Event::dispatch('section.create.after', $section);

        return new JsonResponse([
            'section' => $this->sectionRow($section),
            'message' => trans('admin::app.appearance.sections.create-success'),
        ]);
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

        return redirect()->route('admin.appearance.sections.index', ['code' => $section->theme_code]);
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
     * The field schema for a section together with the values the editor should show,
     * which are its draft when one is pending and its published options otherwise.
     */
    public function fields(int $id): JsonResponse
    {
        $section = $this->sectionRepository->findOrFail($id);

        $translation = $section->translate(core()->getRequestedLocaleCode());

        return new JsonResponse([
            'schema' => app(SectionSchema::class)->for($section->type),
            'options' => $translation?->draft_options ?? $translation?->options ?? (object) [],
        ]);
    }

    /**
     * Store unpublished edits for a section, so the preview can render them.
     */
    public function saveDraft(int $id): JsonResponse
    {
        $this->validate(request(), [
            'options' => 'required|array',
        ]);

        $section = $this->sectionRepository->saveDraft(
            $id,
            core()->getRequestedLocaleCode(),
            request()->input('options')
        );

        return new JsonResponse([
            'has_draft' => $this->hasDraft($section),
        ]);
    }

    /**
     * Store one uploaded image for a section and hand back the path to record in its
     * options, so a schema driven field can upload without knowing the form shape.
     */
    public function uploadMedia(int $id): JsonResponse
    {
        $this->validate(request(), [
            'file' => 'required|mimes:bmp,jpeg,jpg,png,webp,mp4,webm,ogg|max:51200',
        ]);

        return new JsonResponse(
            $this->sectionRepository->storeMedia($id, request()->file('file'))
        );
    }

    /**
     * Publish a section's pending edits to the storefront.
     */
    public function publish(int $id): JsonResponse
    {
        Event::dispatch('section.update.before', $id);

        $section = $this->sectionRepository->publishDraft($id);

        Event::dispatch('section.update.after', $section);

        return new JsonResponse([
            'has_draft' => false,
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }

    /**
     * Throw away a section's pending edits.
     */
    public function discard(int $id): JsonResponse
    {
        $section = $this->sectionRepository->discardDraft($id);

        return new JsonResponse([
            'has_draft' => false,
            'options' => $section->translate(core()->getRequestedLocaleCode())?->options,
            'message' => trans('admin::app.appearance.sections.index.discarded'),
        ]);
    }

    /**
     * Turn a section on or off.
     */
    public function status(int $id): JsonResponse
    {
        $section = $this->sectionRepository->find($id);

        $this->sectionRepository->massUpdateStatus(
            ['status' => request()->boolean('status')],
            [$section->id]
        );

        return new JsonResponse([
            'status' => request()->boolean('status'),
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }

    /**
     * Copy a section, so a similar one does not have to be rebuilt by hand.
     */
    public function duplicate(int $id): JsonResponse
    {
        Event::dispatch('section.create.before');

        $section = $this->sectionRepository->duplicate($id);

        Event::dispatch('section.create.after', $section);

        return new JsonResponse([
            'section' => $this->sectionRow($section),
            'message' => trans('admin::app.appearance.sections.create-success'),
        ]);
    }

    /**
     * Apply a new order after the list has been dragged.
     */
    public function reorder(): JsonResponse
    {
        $this->validate(request(), [
            'sections' => 'required|array|min:1',
            'sections.*' => 'required|integer',
        ]);

        $this->sectionRepository->reorder(request()->input('sections'));

        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }

    /**
     * Endpoints the editor calls, with `__ID__` standing in for the section.
     */
    protected function editorUrls(): array
    {
        return [
            'publish' => route('admin.appearance.sections.publish', ['id' => '__ID__']),
            'discard' => route('admin.appearance.sections.discard', ['id' => '__ID__']),
            'duplicate' => route('admin.appearance.sections.duplicate', ['id' => '__ID__']),
            'status' => route('admin.appearance.sections.status', ['id' => '__ID__']),
            'fields' => route('admin.appearance.sections.fields', ['id' => '__ID__']),
            'draft' => route('admin.appearance.sections.draft', ['id' => '__ID__']),
            'media' => route('admin.appearance.sections.media', ['id' => '__ID__']),
            'delete' => route('admin.appearance.sections.delete', ['id' => '__ID__']),
        ];
    }

    /**
     * Display name for each section type, keyed by the stored value.
     */
    protected function typeLabels(): array
    {
        $prefix = 'admin::app.appearance.sections.create.type.';

        return [
            SectionModel::IMAGE_CAROUSEL => trans($prefix.'image-carousel'),
            SectionModel::PRODUCT_CAROUSEL => trans($prefix.'product-carousel'),
            SectionModel::CATEGORY_CAROUSEL => trans($prefix.'category-carousel'),
            SectionModel::FOOTER_LINKS => trans($prefix.'footer-links'),
            SectionModel::STATIC_CONTENT => trans($prefix.'static-content'),
            SectionModel::SERVICES_CONTENT => trans($prefix.'services-content'),
        ];
    }

    /**
     * Channel the editor is scoped to. Sections are per channel, so the editor edits one
     * at a time and falls back to the current channel.
     */
    protected function requestedChannel()
    {
        $channel = core()->getAllChannels()->firstWhere('id', (int) request('channel'));

        return $channel ?? core()->getCurrentChannel();
    }

    /**
     * The requested theme, or a 404 when the url names one this installation does not
     * have. A theme is part of the path now, so an unknown code is a missing page rather
     * than something to quietly fall back from.
     */
    protected function themeOrFail(string $code): array
    {
        return config('themes.shop.'.$code) ?? abort(404);
    }

    /**
     * Sections of a theme, in render order, shaped for the editor list.
     */
    protected function editableSections(string $themeCode, int $channelId): array
    {
        $locale = core()->getRequestedLocaleCode();

        return $this->sectionRepository
            ->orderBy('sort_order')
            ->findWhere([
                'channel_id' => $channelId,
                'theme_code' => $themeCode,
            ])
            ->map(fn ($section) => $this->sectionRow($section))
            ->values()
            ->toArray();
    }

    /**
     * One section, shaped for the editor list.
     *
     * @param  Section  $section
     */
    protected function sectionRow($section): array
    {
        return [
            'id' => $section->id,
            'name' => $section->name,
            'type' => $section->type,
            'status' => (bool) $section->status,
            'has_draft' => $this->hasDraft($section),
        ];
    }

    /**
     * Whether a section holds edits that are not published yet, in any locale.
     *
     * @param  Section  $section
     */
    protected function hasDraft($section): bool
    {
        return $section->translations->contains(fn ($translation) => ! is_null($translation->draft_options));
    }
}
