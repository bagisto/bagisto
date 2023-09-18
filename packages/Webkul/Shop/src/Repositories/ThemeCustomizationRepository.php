<?php

namespace Webkul\Shop\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
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
                $manager = new ImageManager();

                $image = $manager->make($images)->encode('webp');

                $path = 'theme/' . $theme->id . '/' . Str::random(40) . '.webp';

                Storage::put($path, $image);

                return Storage::url($path);
            }

            foreach ($images as $key => $image) {
                if (isset($image['image']) && $image['image'] instanceof UploadedFile) {
                    $manager = new ImageManager();
    
                    $image = $manager->make($image['image'])->encode('webp');
    
                    $path = 'theme/' . $theme->id . '/' . Str::random(40) . '.webp';
    
                    Storage::put($path, $image);

                    $options['images'][] = [
                        'image' => $path,
                        'link'  => $image['link'],
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