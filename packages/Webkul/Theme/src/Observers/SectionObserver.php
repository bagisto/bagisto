<?php

namespace Webkul\Theme\Observers;

use Illuminate\Support\Facades\Storage;
use Webkul\Theme\Contracts\Section;

class SectionObserver
{
    /**
     * Handle the Section "deleted" event.
     *
     * A section's uploads are filed under its own directory, so they go with it however
     * it is deleted rather than only from the screen that offers the button.
     *
     * @param  Section  $section
     * @return void
     */
    public function deleted($section)
    {
        Storage::deleteDirectory('themes/'.$section->theme_code.'/sections/'.$section->id);
    }
}
