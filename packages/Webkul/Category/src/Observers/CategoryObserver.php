<?php

namespace Webkul\Category\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Category\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "deleted" event.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function deleted($category)
    {
        Storage::deleteDirectory('category/'.$category->id);
    }

    /**
     * Handle the Category "saved" event.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function saved($category)
    {
        foreach ($category->children as $child) {
            $child->touch();
        }
    }
}
