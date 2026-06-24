<?php

namespace Webkul\Theme\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Webkul\Core\Eloquent\Repository;
use Webkul\Theme\Contracts\ThemeCustomization;

class ThemeCustomizationRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ThemeCustomization::class;
    }

    /**
     * Update the specified theme
     *
     * @param  array  $data
     * @param  int  $id
     */
    public function update($data, $id): ThemeCustomization
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

        $theme = parent::update($data, $id);

        if (in_array($data['type'], ['image_carousel', 'services_content'])) {
            $this->uploadImage(request()->all(), $theme);
        }

        return $theme;
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

    /**
     * Mass update the status of themes in the repository.
     *
     * This method updates multiple records in the database based on the provided
     * theme IDs.
     *
     * @param  int  $themeIds
     * @return int The number of records updated.
     */
    public function massUpdateStatus(array $data, array $themeIds)
    {
        return $this->model->whereIn('id', $themeIds)->update($data);
    }

    /**
     * Upload images
     *
     * @return void|string
     */
    public function uploadImage(array $data, ThemeCustomization $theme)
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
                    $path = 'theme/'.$theme->id.'/'.Str::random(40).'.webp';

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

        $translatedModel = $theme->translate($locale);
        $translatedModel->options = $options ?? [];
        $translatedModel->theme_customization_id = $theme->id;
        $translatedModel->save();
    }
}
