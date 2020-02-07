<?php

namespace Webkul\Velocity\Helpers;

use Illuminate\Support\Facades\Storage;
use Webkul\Category\Repositories\CategoryRepository;

class AdminHelper
{
    /**
     * CategoryRepository object
     *
     * @var object
     */
    protected $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository =  $categoryRepository;
    }

    public function saveLocaleImg($locale)
    {
        $data = request()->all();
        $type = 'locale_image';

        if (isset($data['locale_image'])) {
            $request = request();

            foreach ($data['locale_image'] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'velocity/locale_image/' . $locale->id;

                if ($request->hasFile($file)) {
                    if ($locale->{$type}) {
                        Storage::delete($locale->{$type});
                    }

                    $locale->{$type} = $request->file($file)->store($dir);
                    $locale->save();
                }
            }
        } else {
            if ($locale->{$type}) {
                Storage::delete($locale->{$type});
            }

            $locale->{$type} = null;
            $locale->save();
        }

        return $locale;
    }

    public function storeCategoryIcon($category)
    {
        $oldPath = null;
        $iconName = 'category_icon_path.image_1';

        if (gettype($category) !== "object") {
            // getting id on update
            $iconName = 'category_icon_path.image_0';
            $category = $this->categoryRepository->findOrFail($category);

            $oldPath = $category->category_icon_path;
        }

        $uploadedImagePath = $this->uploadImage($category, $iconName, $oldPath);

        if ($uploadedImagePath) {
            $category->category_icon_path = $uploadedImagePath;
            $category->save();
        }

        return $category;
    }

    public function storeSliderDetails($slider)
    {
        $slider->slider_path = request()->get('slider_path');
        $slider->save();

        return true;
    }
}