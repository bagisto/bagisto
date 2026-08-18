<?php

namespace Webkul\Theme\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Webkul\Core\Eloquent\Repository;
use Webkul\Theme\Contracts\Section;

class SectionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Section::class;
    }

    /**
     * Update the specified theme
     *
     * @param  array  $data
     * @param  int  $id
     */
    public function update($data, $id): Section
    {
        $locale = core()->getRequestedLocaleCode();

        if ($data['type'] == 'static_content') {
            $config = [
                'HTML.Allowed' => null,
                'HTML.ForbiddenElements' => 'script,iframe,form',
                'CSS.AllowedProperties' => null,
            ];

            $data[$locale]['options']['html'] = Purify::config($config)->clean($data[$locale]['options']['html'] ?? '');

            $data[$locale]['options']['css'] = $this->sanitizeStaticCss($data[$locale]['options']['css'] ?? '');
        }

        if (in_array($data['type'], ['image_carousel', 'services_content'])) {
            unset($data[$locale]['options']);
        }

        $section = parent::update($data, $id);

        if (in_array($data['type'], ['image_carousel', 'services_content'])) {
            $this->uploadImage(request()->all(), $section);
        }

        return $section;
    }

    /**
     * Mass update the status of themes in the repository.
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
                    $path = 'section/'.$section->id.'/'.Str::random(40).'.webp';

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

        $section->translateOrNew($locale)->draft_options = $options;

        $section->save();

        return $section;
    }

    /**
     * Promote every pending draft of a section to what the storefront renders.
     *
     * Drafts are held per locale, so all of them are published together. Publishing only
     * the locale being viewed would leave the rest pending with nothing to reveal it.
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

            $translation->options = $translation->draft_options;

            $translation->draft_options = null;

            $translation->save();
        }

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

        return $section->refresh();
    }

    /**
     * Set on the request while the appearance preview is rendering.
     *
     * Kept in the internal attribute bag rather than the query, so a visitor cannot ask a
     * storefront page to render unpublished drafts.
     */
    public const PREVIEWING = 'appearance_previewing';

    /**
     * Whether the page being rendered is the appearance preview.
     */
    public function isPreviewing(): bool
    {
        return (bool) request()->attributes->get(self::PREVIEWING, false);
    }

    /**
     * The single section of a type a channel shows, drafted when previewing.
     *
     * Footer links and service promises are rendered by the layout rather than the home
     * page, so they need their own lookup rather than riding along with the section loop.
     */
    public function findOneOfType(string $type, int $channelId, string $themeCode, string $locale)
    {
        $section = $this->findOneWhere([
            'type' => $type,
            'status' => 1,
            'theme_code' => $themeCode,
            'channel_id' => $channelId,
        ]);

        if (
            ! $section
            || ! $this->isPreviewing()
        ) {
            return $section;
        }

        $translation = $section->translate($locale);

        if (
            $translation
            && ! is_null($translation->draft_options)
        ) {
            $translation->options = $translation->draft_options;
        }

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
        return $this->orderBy('sort_order')
            ->findWhere([
                'status' => 1,
                'channel_id' => $channelId,
                'theme_code' => $themeCode,
            ])
            ->each(function ($section) use ($locale) {
                $translation = $section->translate($locale);

                if (
                    ! $translation
                    || is_null($translation->draft_options)
                ) {
                    return;
                }

                $translation->options = $translation->draft_options;
            });
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

        /**
         * Everything below the original shifts down first. Without it the copy shares a
         * sort order with the next section and the tie is broken arbitrarily, which is
         * what sent copies to the bottom of the list.
         */
        $this->model
            ->where('channel_id', $section->channel_id)
            ->where('theme_code', $section->theme_code)
            ->where('sort_order', '>', $section->sort_order)
            ->increment('sort_order');

        $copy = $section->replicateWithTranslations();

        $copy->name = $section->name.' '.trans('admin::app.appearance.sections.index.copy-suffix');

        $copy->status = 0;

        $copy->sort_order = $section->sort_order + 1;

        $copy->save();

        return $copy;
    }

    /**
     * Apply a new order to a set of sections.
     */
    public function reorder(array $sectionIds): void
    {
        /**
         * `update()` is overridden to expect a whole section payload, so the parent is
         * used here to write the single column.
         */
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

        $path = 'section/'.$section->id.'/'.Str::random(40).'.webp';

        Storage::put($path, (string) image_manager()->read($file)->encodeByExtension('webp'));

        return 'storage/'.$path;
    }

    /**
     * Store an uploaded image or video against a section.
     *
     * Images go through the same webp conversion as everywhere else. A video is streamed
     * to disk as uploaded, both because re-encoding one does not belong in a request and
     * because the image library cannot read it.
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
            'section/'.$section->id,
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
    protected function sanitizeStaticCss(?string $css): string
    {
        $css = str_replace("\0", '', (string) $css);

        return str_ireplace('</style', '<\/style', $css);
    }
}
