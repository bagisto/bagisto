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
        $locale->locale_image = $this->uploadImage('locale_image');
        $locale->save();

        return $locale;
    }

    public function storeCategoryIcon($categoryId)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);

        $category->category_icon_path = $this->uploadImage('category_icon_path');
        $category->save();

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
        }

        return $image;
    }
}