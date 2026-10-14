<?php

namespace Webkul\Category\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Category\Contracts\Category;

class CategoryObserver
{
    /**
     * Handle the Category "deleted" event.
     *
     * @param  Category  $category
     * @return void
     */
    public function deleted($category)
    {
        Storage::deleteDirectory('categories/'.$category->id);
    }

    /**
     * Handle the Category "saved" event. Every descendant is reached through this category's url,
     * so the whole subtree's last-modified date moves with it, in one statement over the nested set.
     *
     * @param  Category  $category
     * @return void
     */
    public function saved($category)
    {
        $category->descendants()->touch();
    }
}
