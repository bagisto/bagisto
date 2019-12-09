<?php

namespace Webkul\Category\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Category\Models\Category;
use Carbon\Carbon;

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

    /**
     * Handle the Category "saved" event.
     *
     * @param Category $category
     */
    public function saved($category)
    {
        foreach($category->children as $child) {
            // Hacky trick to make this method unit-testable (instead of $child->touch()
            // as the unit test is too fast)
            $child->setUpdatedAt(Carbon::now()->addSecond());

            $child->save();
        }
    }
}