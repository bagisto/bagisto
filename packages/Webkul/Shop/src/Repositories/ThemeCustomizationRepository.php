<?php

namespace Webkul\Shop\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Shop\Contracts\ThemeCustomization;

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
     * Upload images
     *
     * @param  array  $imageOptions
     * @param  \Webkul\Shop\Contracts\ThemeCustomization  $theme
     * 
     * @return void|string
     */
    public function uploadImage($imageOptions, $theme, $deletedSliderImages = [])
    {
        foreach ($deletedSliderImages as $slider) {
            Storage::delete(str_replace('storage/', '', $slider['image']));
        }

        foreach($imageOptions as $images) {
            if (empty($images) || is_string($images)) {
                continue;
            }

            if ($images instanceof UploadedFile) {
                $folder = 'theme/' . $theme->id;

                $fileName = uniqid('static_content') . '.' . $images->getClientOriginalExtension();

                return '<img class="lazy" data-src="'. Storage::url($images->storeAs($folder, $fileName)) .'">';
            }

            foreach ($images as $key => $image) {
                if (isset($image['image']) && $image['image'] instanceof UploadedFile) {
                    $options['images'][] = [
                        'image' =>  'storage/' . $image['image']->storeAs(
                            'theme/' . $theme->id, ++$key . '.' . $image['image']->getClientOriginalExtension()
                        ),
                        'link' => $image['link'],
                    ];
                } else {
                    foreach ($imageOptions['options'] as $option) {
                        if (! $option['image'] instanceof UploadedFile) {
                            $previousOptions[] = $option;
                        }
                    }

                    $options['images'] = $previousOptions ?? [];
                }
            }   
        }

        $theme->options = $options ?? [];
    
        $theme->save();
    }
}