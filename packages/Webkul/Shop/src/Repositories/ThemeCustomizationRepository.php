<?php

namespace Webkul\Shop\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\StorageAttributes;
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
    public function uploadImage($imageOptions, $theme, $removeImages = [])
    {
        $options = [];

        if (! empty($imageOptions)) {
            foreach($imageOptions as $images) {
                foreach ($images as $key => $image) {
                    if (isset($image['image']) && $image['image'] instanceof UploadedFile) {
                        $options['images'][] = [
                            'image' =>  'storage/' . $image['image']->storeAs(
                                'theme/' . $theme->id, ++$key . '.' . $image['image']->getClientOriginalExtension()
                            ),
                            'link' => $image['link'],
                        ];
                    } else {
                        $options['images'] = $theme->options['images'];
                    }
                }   
            }

            $theme->options = $options;
        
            $theme->save();
        }
    }
}
?>