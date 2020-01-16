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
        $uploadedImagePath = $this->uploadImage('locale_image.image_0');

        if ($uploadedImagePath) {
            $locale->locale_image = $uploadedImagePath;
            $locale->save();
        }

        return $locale;
    }

    public function storeCategoryIcon($categoryId)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        $uploadedImagePath = $this->uploadImage('category_icon_path.image_0');

        if ($uploadedImagePath) {
            $category->category_icon_path = $uploadedImagePath;
            $category->save();
        }

        return $category;
    }

    public function uploadImage($type)
    {
        $request = request();

        $image = '';
        $file = $type;
        $dir = "velocity/$type";

        if ($request->hasFile($file)) {
            Storage::delete($dir . $file);

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