<?php

namespace Webkul\Category\Observers;

use Illuminate\Support\Facades\Storage;

class CategoryObserver
{
    /**
     * Handle the Category "deleted" event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleted($category)
    {
        Storage::deleteDirectory('category/' . $category->id);
    }
}