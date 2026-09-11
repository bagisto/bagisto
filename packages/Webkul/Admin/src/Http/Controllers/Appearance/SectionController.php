<?php

namespace Webkul\Admin\Http\Controllers\Appearance;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Contracts\Section;
use Webkul\Theme\Repositories\SectionRepository;
use Webkul\Theme\Sections\SectionType;
use Webkul\Theme\SectionSchema;
use Webkul\Theme\ThemeCatalog;

class SectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        public SectionRepository $sectionRepository,
        protected ThemeCatalog $themeCatalog,
        protected SectionSchema $sectionSchema
    ) {}

    /**
     * Display the section editor for a theme, on a channel that runs it.
     *
     * @return View|RedirectResponse
     */
    public function index(string $code)
    {
        $theme = $this->themeOrFail($code);

        $channel = $this->channelRunning($code);

        if (! $channel) {
            session()->flash('warning', trans('admin::app.appearance.sections.index.inactive-theme'));

            return redirect()->route('admin.appearance.themes.index');
        }

        $locale = $this->requestedLocale($channel);

        return view('admin::appearance.sections.index', [
            'scopedTheme' => $code,
            'scopedThemeName' => $theme['name'],
            'scopedChannel' => $channel,
            'scopedLocale' => $locale,
            'channels' => core()->getAllChannels(),
            'locales' => $channel->locales,
            'sections' => $this->editableSections($code, $channel->id),
            'sectionTypes' => $this->sectionSchema->types($code)
                ->map(fn (SectionType $type) => $type->toArray())
                ->values()
                ->all(),
            'previewUrl' => route('shop.appearance.preview', [
                'theme' => $code,
                'channel' => $channel->id,
                'locale' => $locale->code,
            ]),
            'publishUrl' => route('admin.appearance.sections.publish', ['code' => $code]),
            'discardUrl' => route('admin.appearance.sections.discard', ['code' => $code]),
            'urls' => $this->editorUrls(),
        ]);
    }

    /**
     * Create a section against the theme and channel the editor is scoped to, held back from the
     * storefront until it is published.
     *
     * @return JsonResponse
     */
    public function store(string $code)
    {
        $this->themeOrFail($code);

        $validated = $this->validate(request(), [
            'name' => 'required',
            'type' => ['required', Rule::in($this->sectionSchema->types($code)->keys()->all())],
        ]);

        $channel = $this->customizableChannel($code);

        $this->guardSingleton($validated['type'], $code, $channel->id);

        Event::dispatch('section.create.before');

        $section = $this->sectionRepository->create($validated + [
            'channel_id' => $channel->id,
            'theme_code' => $code,
            'sort_order' => count($this->editableSections($code, $channel->id)) + 1,
            'status' => 0,
            'draft_status' => true,
        ]);

        Event::dispatch('section.create.after', $section);

        $sections = $this->editableSections($code, $channel->id);

        $this->sectionRepository->reorder(array_column($sections, 'id'));

        return new JsonResponse([
            'section' => $this->sectionRow($section->refresh()),
            'message' => trans('admin::app.appearance.sections.create-success'),
        ]);
    }

    /**
     * Update the specified section.
     *
     * @return RedirectResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'sort_order' => 'required|numeric',
            'type' => ['required', Rule::in($this->sectionSchema->types(request('theme_code'))->keys()->all())],
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck('id')->toArray())),
            'theme_code' => 'required',
        ]);

        $this->sectionOrFail($id);

        abort_unless(
            $this->themeCatalog->isActive((string) request('theme_code'), (int) request('channel_id')),
            $this->inactiveThemeResponse()
        );

        $this->guardSingleton(
            request('type'),
            request('theme_code'),
            (int) request('channel_id'),
            $id
        );

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
     * Delete the specified section.
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $this->sectionOrFail($id);

        Event::dispatch('section.delete.before', $id);

        $this->sectionRepository->delete($id);

        Event::dispatch('section.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.delete-success'),
        ], 200);
    }

    /**
     * The field schema for a section together with the values the editor should show, which are
     * its draft when one is pending and its published options otherwise.
     */
    public function fields(int $id): JsonResponse
    {
        $section = $this->sectionOrFail($id);

        $translation = $section->translate(core()->getRequestedLocaleCode());

        $type = $section->getTypeInstance();

        $options = $translation?->draft_options ?? $translation?->options ?? [];

        return new JsonResponse([
            'schema' => $type?->getFields() ?? [],
            'options' => ($type?->prepareForEditor($options) ?? $options) ?: (object) [],
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

        $this->sectionOrFail($id);

        Event::dispatch('section.draft.save.before', $id);

        $section = $this->sectionRepository->saveDraft(
            $id,
            core()->getRequestedLocaleCode(),
            request()->input('options')
        );

        Event::dispatch('section.draft.save.after', $section);

        return new JsonResponse([
            'has_draft' => $this->sectionRepository->hasDraft($section),
        ]);
    }

    /**
     * Store one uploaded file for a section and hand back the path to record in its options.
     */
    public function uploadMedia(int $id): JsonResponse
    {
        $this->validate(request(), [
            'file' => 'required|mimes:bmp,jpeg,jpg,png,webp,mp4,webm,ogg|max:51200',
        ]);

        $this->sectionOrFail($id);

        Event::dispatch('section.media.upload.before', $id);

        $media = $this->sectionRepository->storeMedia($id, request()->file('file'));

        Event::dispatch('section.media.upload.after', $media);

        return new JsonResponse($media);
    }

    /**
     * Publish every pending edit of a theme's channel to the storefront, as a whole set because
     * ordering is relative across the sections.
     */
    public function publish(string $code): JsonResponse
    {
        $this->themeOrFail($code);

        $channel = $this->customizableChannel($code);

        $drafted = $this->sectionRepository->draftedSections($channel->id, $code);

        $drafted->each(fn ($section) => Event::dispatch('section.update.before', $section->id));

        $published = $this->sectionRepository->publishDrafts($drafted);

        $published->each(fn ($section) => Event::dispatch('section.update.after', $section));

        return new JsonResponse([
            'published' => $published->count(),
            'sections' => $this->editableSections($code, $channel->id),
            'message' => trans('admin::app.appearance.sections.update-success'),
        ]);
    }

    /**
     * Throw away every pending edit of a theme's channel.
     */
    public function discard(string $code): JsonResponse
    {
        $this->themeOrFail($code);

        $channel = $this->customizableChannel($code);

        $drafted = $this->sectionRepository->draftedSections($channel->id, $code);

        $drafted->each(fn ($section) => Event::dispatch('section.draft.discard.before', $section->id));

        $discarded = $this->sectionRepository->discardDrafts($drafted);

        $discarded->each(fn ($section) => Event::dispatch('section.draft.discard.after', $section));

        return new JsonResponse([
            'discarded' => $discarded->count(),
            'sections' => $this->editableSections($code, $channel->id),
            'message' => trans('admin::app.appearance.sections.index.discarded'),
        ]);
    }

    /**
     * Turn a section on or off.
     */
    public function status(int $id): JsonResponse
    {
        $this->sectionOrFail($id);

        Event::dispatch('section.draft.save.before', $id);

        $section = $this->sectionRepository->saveStatusDraft($id, request()->boolean('status'));

        Event::dispatch('section.draft.save.after', $section);

        return new JsonResponse([
            'status' => request()->boolean('status'),
            'has_draft' => $this->sectionRepository->hasDraft($section),
        ]);
    }

    /**
     * Copy a section, so a similar one does not have to be rebuilt by hand.
     */
    public function duplicate(int $id): JsonResponse
    {
        $section = $this->sectionOrFail($id);

        $this->guardSingleton($section->type, $section->theme_code, $section->channel_id);

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

        $sections = $this->sectionRepository->findWhereIn('id', request()->input('sections'));

        abort_if(
            $sections->contains(fn ($section) => ! $this->isCustomizable($section)),
            $this->inactiveThemeResponse()
        );

        $sectionIds = $this->withPinnedLast(request()->input('sections'), $sections);

        Event::dispatch('section.reorder.before', $sectionIds);

        $this->sectionRepository->saveOrderDraft($sectionIds);

        $sections = $this->sectionRepository->findWhereIn('id', $sectionIds);

        Event::dispatch('section.reorder.after', $sections);

        return new JsonResponse([
            'pending' => $sections->mapWithKeys(fn ($section) => [
                $section->id => $this->sectionRepository->hasDraft($section),
            ]),
        ]);
    }

    /**
     * Endpoints the editor calls, with `__ID__` standing in for the section.
     */
    protected function editorUrls(): array
    {
        return [
            'duplicate' => route('admin.appearance.sections.duplicate', ['id' => '__ID__']),
            'status' => route('admin.appearance.sections.status', ['id' => '__ID__']),
            'fields' => route('admin.appearance.sections.fields', ['id' => '__ID__']),
            'draft' => route('admin.appearance.sections.draft', ['id' => '__ID__']),
            'media' => route('admin.appearance.sections.media', ['id' => '__ID__']),
            'delete' => route('admin.appearance.sections.delete', ['id' => '__ID__']),
        ];
    }

    /**
     * The channel a request names, falling back to the default channel.
     *
     * @return Channel
     */
    protected function requestedChannel()
    {
        $channel = core()->getAllChannels()->firstWhere('id', (int) request('channel'));

        return $channel ?? core()->getDefaultChannel();
    }

    /**
     * The requested channel when it runs the theme, otherwise the first channel that does.
     *
     * @return Channel|null
     */
    protected function channelRunning(string $code)
    {
        $channels = $this->themeCatalog->activeChannels($code);

        return $channels->firstWhere('id', (int) request('channel')) ?? $channels->first();
    }

    /**
     * The requested channel, refused unless it currently runs the theme.
     *
     * @return Channel
     */
    protected function customizableChannel(string $code)
    {
        $channel = $this->requestedChannel();

        abort_unless($this->themeCatalog->isActive($code, $channel->id), $this->inactiveThemeResponse());

        return $channel;
    }

    /**
     * The locale being edited, which has to be one the channel actually runs.
     *
     * @param  Channel  $channel
     */
    protected function requestedLocale($channel)
    {
        $locales = $channel->locales;

        return $locales->firstWhere('code', request('locale'))
            ?? $locales->firstWhere('code', app()->getLocale())
            ?? $locales->first()
            ?? core()->getCurrentLocale();
    }

    /**
     * The section being acted on, as a 404 once it is gone or a 403 when its theme is inactive.
     */
    protected function sectionOrFail(int $id): Section
    {
        $section = $this->sectionRepository->find($id);

        abort_unless($section, new JsonResponse([
            'message' => trans('admin::app.appearance.sections.index.gone'),
        ], 404));

        abort_unless($this->isCustomizable($section), $this->inactiveThemeResponse());

        return $section;
    }

    /**
     * Whether a section belongs to the theme its channel currently runs.
     *
     * @param  Section  $section
     */
    protected function isCustomizable($section): bool
    {
        return $this->themeCatalog->isActive((string) $section->theme_code, (int) $section->channel_id);
    }

    /**
     * The answer given when a theme that is not active is asked to be customized.
     */
    protected function inactiveThemeResponse(): JsonResponse
    {
        return new JsonResponse([
            'message' => trans('admin::app.appearance.sections.index.inactive-theme'),
        ], 403);
    }

    /**
     * The requested theme from the catalog, or a 404 when this installation does not have it.
     */
    protected function themeOrFail(string $code): array
    {
        abort_unless($this->themeCatalog->isInstalled($code), 404);

        return $this->themeCatalog->find($code);
    }

    /**
     * Sections of a theme, in render order with the pinned ones last, shaped for the editor list.
     */
    protected function editableSections(string $themeCode, int $channelId): array
    {
        return $this->sectionRepository
            ->orderBy('sort_order')
            ->findWhere([
                'channel_id' => $channelId,
                'theme_code' => $themeCode,
            ])
            ->sortBy(fn ($section) => $this->isPinned($section) ? 1 : 0)
            ->map(fn ($section) => $this->sectionRow($section))
            ->values()
            ->toArray();
    }

    /**
     * The given order with the pinned sections moved to the end, so a reorder cannot lift them.
     */
    protected function withPinnedLast(array $sectionIds, $sections): array
    {
        $pinned = $sections
            ->filter(fn ($section) => $this->isPinned($section))
            ->pluck('id')
            ->all();

        $free = array_values(array_diff($sectionIds, $pinned));

        return array_merge($free, array_values(array_intersect($sectionIds, $pinned)));
    }

    /**
     * Refuse a second section of a type a channel may hold only one of, however it is reached.
     */
    protected function guardSingleton(?string $type, ?string $themeCode, int $channelId, ?int $ignoreId = null): void
    {
        $sectionType = $this->sectionSchema->type($themeCode, $type);

        if (! $sectionType?->isSingleton()) {
            return;
        }

        $existing = $this->sectionRepository
            ->findWhere([
                'type' => $type,
                'theme_code' => $themeCode,
                'channel_id' => $channelId,
            ])
            ->filter(fn ($section) => $section->id !== $ignoreId);

        if ($existing->isEmpty()) {
            return;
        }

        throw ValidationException::withMessages([
            'type' => trans('admin::app.appearance.sections.create.singleton-exists', [
                'type' => $sectionType->getTitle(),
            ]),
        ]);
    }

    /**
     * Whether a section is fixed to the bottom of the page.
     *
     * @param  Section  $section
     */
    protected function isPinned($section): bool
    {
        return (bool) $section->getTypeInstance()?->isPinned();
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
            'status' => (bool) ($section->draft_status ?? $section->status),
            'has_draft' => $this->sectionRepository->hasDraft($section),
            'is_pinned' => $this->isPinned($section),
        ];
    }
}
