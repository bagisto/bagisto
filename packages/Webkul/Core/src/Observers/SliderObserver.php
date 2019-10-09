<?php

namespace Webkul\Core\Observers;

use Illuminate\Support\Facades\Storage;

class SliderObserver
{
    /**
     * Handle the Slider "deleted" event.
     *
     * @param  Slider $slider
     * @return void
     */
    public function deleted($slider)
    {
        Storage::delete($slider->path);
    }
}