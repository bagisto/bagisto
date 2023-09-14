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
     * @return void
     */
    public function uploadImage($imageOptions, $theme, $deletedSliderImages)
    {
        foreach ($deletedSliderImages as $slider) {
            Storage::delete(str_replace('storage/', '', $slider['image']));
        }

        foreach($imageOptions as $images) {
            foreach ($images as $image) {
                if (isset($image['image']) && $image['image'] instanceof UploadedFile) {
                    $options['images'][] = [
                        'image' =>  'storage/' . $image['image']->storeAs(
                            'theme/' . $theme->id, count($theme->options['images']) + 1 . '.' . $image['image']->getClientOriginalExtension()
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