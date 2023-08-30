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
     * @param  array  $images
     * @param  \Webkul\Shop\Contracts\ThemeCustomization  $theme
     * 
     * @return void
     */
    public function uploadImage($images, $theme, $removeImages = [])
    {
        $options = ! empty($theme->options) ? $theme->options : [];

        if (! empty($removeImages)) {
            $options = [];

            $result = array_diff($theme->options['images'], $removeImages);

            Storage::delete($result);

            foreach ($result as $value) {
                $options['images'][]  = $value;
            }
        }

        if (! empty($images)) {
            foreach($images as $key => $image) {
                if ($image instanceof UploadedFile) {
                    $options['images'][] = 'storage/' . $image->storeAs(
                        'theme/' . $theme->id, ++$key . '.' . $image->getClientOriginalExtension()
                    );
                }
            }
        }

         
        $theme->options = $options;
    
        $theme->save();
    }
}
?>