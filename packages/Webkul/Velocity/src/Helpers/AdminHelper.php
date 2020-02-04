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
        $uploadedImagePath = $this->uploadImage($locale, 'locale_image.image_0');

        if ($uploadedImagePath) {
            $locale->locale_image = $uploadedImagePath;
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

    public function uploadImage($record, $type, $oldPath = null)
    {
        $request = request();

        $image = '';
        $file = $type;
        $dir = 'velocity/' . $type;

        if ($request->hasFile($file)) {
            if ($oldPath) {
                Storage::delete($oldPath);
            }

            $image = $request->file($file)->store($dir);

            return $image;
        }
        return false;
    }

    public function storeSliderDetails($slider)
    {
        $slider->slider_path = request()->get('slider_path');
        $slider->save();

        return true;
    }
}