<?php

namespace Webkul\Theme\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Webkul\Core\Eloquent\Repository;
use Webkul\Theme\Contracts\Section;

class SectionRepository extends Repository
{
    /**
     * Set on the request while the appearance preview is rendering.
     *
     * Kept in the internal attribute bag rather than the query, so a visitor cannot ask a
     * storefront page to render unpublished drafts.
     */
    public const PREVIEWING = 'appearance_previewing';

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Section::class;
    }

    /**
     * Update the specified section.
     *
     * @param  array  $data
     * @param  int  $id
     */
    public function update($data, $id): Section
    {
        $locale = core()->getRequestedLocaleCode();

        if ($data['type'] == 'static_content') {
            $data[$locale]['options'] = $this->sanitizeOptions(
                $data['type'],
                $data[$locale]['options'] ?? []
            );
        }

        if (in_array($data['type'], ['image_carousel', 'services_content'])) {
            unset($data[$locale]['options']);
        }

        $section = parent::update($data, $id);

        if (in_array($data['type'], ['image_carousel', 'services_content'])) {
            $this->uploadImage(request()->all(), $section);
        }

        $this->purgeUnreferencedMedia($section);

        return $section;
    }

    /**
     * Mass update the status of sections in the repository.
     *
     * This method updates multiple records in the database based on the provided
     * section ids.
     *
     * @param  int  $sectionIds
     * @return int The number of records updated.
     */
    public function massUpdateStatus(array $data, array $sectionIds)
    {
        return $this->model->whereIn('id', $sectionIds)->update($data);
    }

    /**
     * Upload images
     *
     * @return void|string
     */
    public function uploadImage(array $data, Section $section)
    {
        $locale = core()->getRequestedLocaleCode();

        if (isset($data[$locale]['deleted_sliders'])) {
            foreach ($data[$locale]['deleted_sliders'] as $slider) {
                Storage::delete(str_replace('storage/', '', $slider['image']));
            }
        }

        if (! isset($data[$locale]['options'])) {
            return;
        }

        $options = [];

        foreach ($data[$locale]['options'] as $image) {
            if (isset($image['service_icon'])) {
                $options['services'][] = [
                    'service_icon' => $image['service_icon'],
                    'description' => $image['description'],
                    'title' => $image['title'],
                ];
            } elseif ($image['image'] instanceof UploadedFile) {
                try {
                    $path = $this->mediaDirectory($section).'/'.Str::random(40).'.webp';

                    $encoded = image_manager()->read($image['image'])->encodeByExtension('webp');

                    Storage::put($path, (string) $encoded);
                } catch (\Exception $e) {
                    session()->flash('error', $e->getMessage());

                    return redirect()->back();
                }

                if (($data['type'] ?? '') == 'static_content') {
                    return Storage::url($path);
                }

                $options['images'][] = [
                    'image' => 'storage/'.$path,
                    'link' => $image['link'],
                    'title' => $image['title'],
                ];
            } else {
                $options['images'][] = $image;
            }
        }

        $translatedModel = $section->translate($locale);
        $translatedModel->options = $options ?? [];
        $translatedModel->section_id = $section->id;
        $translatedModel->save();
    }

    /**
     * Store unpublished edits for a section, in the given locale.
     *
     * @param  int  $id
     */
    public function saveDraft($id, string $locale, array $options): Section
    {
        $section = $this->findOrFail($id);

        $section->translateOrNew($locale)->draft_options = $this->sanitizeOptions($section->type, $options);

        $section->save();

        $this->purgeUnreferencedMedia($section);

        return $section;
    }

    /**
     * Hold a section's new on or off state, until it is published.
     *
     * @param  int  $id
     */
    public function saveStatusDraft($id, bool $status): Section
    {
        $section = $this->findOrFail($id);

        $section->draft_status = $status === (bool) $section->status ? null : $status;

        $section->save();

        return $section->refresh();
    }

    /**
     * Hold a new order for the given sections, until it is published.
     */
    public function saveOrderDraft(array $sectionIds): void
    {
        $sections = $this->findWhereIn('id', $sectionIds)->keyBy('id');

        $this->closeOrderGaps($sections);

        foreach (array_values($sectionIds) as $position => $id) {
            $section = $sections->get($id);

            if (! $section) {
                continue;
            }

            $order = $position + 1;

            $section->draft_sort_order = $order === (int) $section->sort_order ? null : $order;

            $section->save();
        }
    }

    /**
     * Number the sections from one, without changing the order they are already in.
     *
     * Copying and deleting leave gaps in the stored numbers, and a dragged list is read
     * back as positions. Comparing the two would report every section as moved the moment
     * any one of them was, so the gaps are closed before the new positions are held.
     */
    protected function closeOrderGaps(Collection $sections): void
    {
        $position = 0;

        foreach ($sections->sortBy('sort_order') as $section) {
            $position++;

            if ((int) $section->sort_order === $position) {
                continue;
            }

            $section->sort_order = $position;

            $section->save();
        }
    }

    /**
     * Whether a section is holding any change the storefront has not been given yet.
     *
     * @param  Section  $section
     */
    public function hasDraft($section): bool
    {
        return ! is_null($section->draft_status)
            || ! is_null($section->draft_sort_order)
            || $section->translations->contains(fn ($translation) => ! is_null($translation->draft_options));
    }

    /**
     * Promote every pending draft of a section, in every locale, to what the storefront
     * renders.
     *
     * @param  int  $id
     */
    public function publishDraft($id): Section
    {
        $section = $this->findOrFail($id);

        foreach ($section->translations as $translation) {
            if (is_null($translation->draft_options)) {
                continue;
            }

            $translation->options = $this->sanitizeOptions($section->type, $translation->draft_options);

            $translation->draft_options = null;

            $translation->save();
        }

        if (! is_null($section->draft_status)) {
            $section->status = $section->draft_status;
        }

        if (! is_null($section->draft_sort_order)) {
            $section->sort_order = $section->draft_sort_order;
        }

        $section->draft_status = null;

        $section->draft_sort_order = null;

        $section->save();

        $this->purgeUnreferencedMedia($section);

        return $section->refresh();
    }

    /**
     * Throw away every pending draft of a section.
     *
     * @param  int  $id
     */
    public function discardDraft($id): Section
    {
        $section = $this->findOrFail($id);

        foreach ($section->translations as $translation) {
            if (is_null($translation->draft_options)) {
                continue;
            }

            $translation->draft_options = null;

            $translation->save();
        }

        $section->draft_status = null;

        $section->draft_sort_order = null;

        $section->save();

        $this->purgeUnreferencedMedia($section);

        return $section->refresh();
    }

    /**
     * The sections of a theme's channel that are holding a change.
     */
    public function draftedSections(int $channelId, string $themeCode): Collection
    {
        return $this->findWhere([
            'channel_id' => $channelId,
            'theme_code' => $themeCode,
        ])->filter(fn ($section) => $this->hasDraft($section))->values();
    }

    /**
     * Promote the given sections' pending drafts in one transaction.
     *
     * Ordering is relative across the whole set, so a draft is never published on its own —
     * publishing one section of a reorder would leave the rest holding the old positions.
     *
     * @return Collection The sections as they now stand.
     */
    public function publishDrafts(Collection $sections): Collection
    {
        return $this->runOnDrafted($sections, fn ($section) => $this->publishDraft($section->id));
    }

    /**
     * Throw away the given sections' pending drafts in one transaction.
     *
     * @return Collection The sections as they now stand.
     */
    public function discardDrafts(Collection $sections): Collection
    {
        return $this->runOnDrafted($sections, fn ($section) => $this->discardDraft($section->id));
    }

    /**
     * Apply the callback to each given section, settling them together.
     */
    protected function runOnDrafted(Collection $sections, callable $callback): Collection
    {
        if ($sections->isEmpty()) {
            return $sections;
        }

        return DB::transaction(fn () => $sections->map($callback));
    }

    /**
     * The sections matching the criteria that are live on the storefront.
     */
    protected function live(array $criteria)
    {
        return $this->orderBy('sort_order')->findWhere($criteria + ['status' => 1]);
    }

    /**
     * The sections matching the criteria as the editor is holding them.
     *
     * A staged change lives beside the column it will replace, so it is resolved once the
     * rows are in hand rather than in the query.
     */
    protected function drafted(array $criteria, string $locale)
    {
        return $this->orderBy('sort_order')
            ->findWhere($criteria)
            ->each(fn ($section) => $this->applyDraft($section, $locale))
            ->filter(fn ($section) => (bool) $section->status)
            ->sortBy('sort_order')
            ->values();
    }

    /**
     * Whether the page being rendered is the appearance preview.
     */
    public function isPreviewing(): bool
    {
        return (bool) request()->attributes->get(self::PREVIEWING, false);
    }

    /**
     * Every section a channel shows on its home page, in render order.
     */
    public function getRenderable(int $channelId, string $themeCode)
    {
        return $this->live([
            'channel_id' => $channelId,
            'theme_code' => $themeCode,
        ]);
    }

    /**
     * Every section of a type a channel shows, in render order and drafted when previewing.
     */
    public function findAllOfType(string $type, int $channelId, string $themeCode, string $locale)
    {
        $criteria = [
            'type' => $type,
            'theme_code' => $themeCode,
            'channel_id' => $channelId,
        ];

        return $this->isPreviewing()
            ? $this->drafted($criteria, $locale)
            : $this->live($criteria);
    }

    /**
     * The single section of a type a channel shows, drafted when previewing.
     *
     * Footer links and service promises are rendered by the layout rather than the home
     * page, so they need their own lookup rather than riding along with the section loop.
     */
    public function findOneOfType(string $type, int $channelId, string $themeCode, string $locale)
    {
        $section = $this->findAllOfType($type, $channelId, $themeCode, $locale)->first();

        return $section;
    }

    /**
     * Sections a channel renders, with each one's options resolved to its draft where a
     * draft exists, so that the editor preview shows unpublished work.
     *
     * @return Collection
     */
    public function getDraftedForPreview(int $channelId, string $themeCode, string $locale)
    {
        return $this->drafted([
            'channel_id' => $channelId,
            'theme_code' => $themeCode,
        ], $locale);
    }

    /**
     * Copy a section, including its translated options, so a similar one does not have to
     * be rebuilt by hand.
     *
     * @param  int  $id
     */
    public function duplicate($id): Section
    {
        $section = $this->findOrFail($id);

        $this->makeRoomBelow($section);

        $copy = $section->replicateWithTranslations();

        $copy->name = $section->name.' '.trans('admin::app.appearance.sections.index.copy-suffix');

        $copy->sort_order = $section->sort_order + 1;

        $copy->status = 0;

        $copy->draft_status = true;

        $copy->draft_sort_order = null;

        $copy->save();

        return $copy;
    }

    /**
     * Apply a new order to a set of sections.
     *
     * The sort order is written through the parent, because this repository's `update()`
     * expects a whole section payload.
     */
    public function reorder(array $sectionIds): void
    {
        foreach (array_values($sectionIds) as $position => $sectionId) {
            parent::update(['sort_order' => $position + 1], $sectionId);
        }
    }

    /**
     * Store a single uploaded image against a section, returning the path as the
     * storefront records it.
     *
     * @param  int  $id
     */
    public function storeImage($id, UploadedFile $file): string
    {
        $section = $this->findOrFail($id);

        $path = $this->mediaDirectory($section).'/'.Str::random(40).'.webp';

        Storage::put($path, (string) image_manager()->read($file)->encodeByExtension('webp'));

        return 'storage/'.$path;
    }

    /**
     * Store an uploaded image or video against a section, converting an image to webp and
     * streaming a video to disk as uploaded.
     *
     * @return array{path: string, type: string}
     */
    public function storeMedia($id, UploadedFile $file): array
    {
        $section = $this->findOrFail($id);

        if (! Str::startsWith((string) $file->getMimeType(), 'video/')) {
            return [
                'path' => $this->storeImage($section->id, $file),
                'type' => 'image',
            ];
        }

        $path = Storage::putFileAs(
            $this->mediaDirectory($section),
            $file,
            Str::random(40).'.'.$file->extension()
        );

        return [
            'path' => 'storage/'.$path,
            'type' => 'video',
        ];
    }

    /**
     * Sanitize custom static-content CSS.
     *
     * CSS is not HTML, so it must not be passed through the HTML purifier - doing
     * so entity-encodes valid characters (e.g. the ">" child combinator becomes
     * "&gt;") and breaks the stylesheet. Because the value is rendered verbatim
     * inside a <style> block, the only way to break out of that context is a
     * literal "</style" sequence, so that (and null bytes) is all we neutralize.
     */
    /**
     * Clean the options a section is given.
     *
     * Static content is written into the page as markup and styles rather than escaped,
     * so it is cleaned wherever it is stored, not only on the form that first took it.
     */
    protected function sanitizeOptions(?string $type, array $options): array
    {
        if ($type !== 'static_content') {
            return $options;
        }

        $config = [
            'HTML.Allowed' => null,
            'HTML.ForbiddenElements' => 'script,iframe,form',
            'CSS.AllowedProperties' => null,
        ];

        if (array_key_exists('html', $options)) {
            $options['html'] = Purify::config($config)->clean((string) $options['html']);
        }

        if (array_key_exists('css', $options)) {
            $options['css'] = $this->sanitizeStaticCss($options['css']);
        }

        return $options;
    }

    /**
     * Strip what would let custom css break out of the style block it is written into.
     */
    protected function sanitizeStaticCss(?string $css): string
    {
        $css = str_replace("\0", '', (string) $css);

        return str_ireplace('</style', '<\/style', $css);
    }

    /**
     * Swap a section's options for its draft, while previewing.
     *
     * @param  Section  $section
     */
    protected function applyDraft($section, string $locale): void
    {
        $translation = $section->translate($locale);

        if (
            $translation
            && ! is_null($translation->draft_options)
        ) {
            $translation->options = $this->sanitizeOptions($section->type, $translation->draft_options);
        }

        if (! is_null($section->draft_status)) {
            $section->status = $section->draft_status;
        }

        if (! is_null($section->draft_sort_order)) {
            $section->sort_order = $section->draft_sort_order;
        }
    }

    /**
     * Delete the uploads a section no longer points at.
     *
     * An upload is reachable from the options the storefront renders and from the draft
     * waiting to replace them, in any locale, so a file is only spare once neither
     * mentions it. Matching on the stored name covers a path recorded on its own as well
     * as one written into custom html.
     *
     * @param  Section  $section
     */
    protected function purgeUnreferencedMedia($section): void
    {
        $directory = $this->mediaDirectory($section);

        if (! Storage::exists($directory)) {
            return;
        }

        $referenced = $section->refresh()->translations
            ->map(fn ($translation) => json_encode([$translation->options, $translation->draft_options]))
            ->implode(' ');

        foreach (Storage::files($directory) as $file) {
            if (! str_contains($referenced, basename($file))) {
                Storage::delete($file);
            }
        }
    }

    /**
     * Where a section's uploads are kept, filed under the theme they belong to.
     *
     * @param  Section  $section
     */
    protected function mediaDirectory($section): string
    {
        return 'themes/'.$section->theme_code.'/sections/'.$section->id;
    }

    /**
     * Push every section below this one down a place, freeing the slot underneath it.
     *
     * @param  Section  $section
     */
    protected function makeRoomBelow($section): void
    {
        $this->model
            ->where('channel_id', $section->channel_id)
            ->where('theme_code', $section->theme_code)
            ->where('sort_order', '>', $section->sort_order)
            ->increment('sort_order');
    }
}
