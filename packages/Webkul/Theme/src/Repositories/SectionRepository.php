<?php

namespace Webkul\Theme\Repositories;

use Illuminate\Http\UploadedFile;
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
