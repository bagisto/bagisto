<?php

namespace Webkul\Custom\Listeners;

class CustomSliderEventsHandler
{
    public function createAfter()
    {
        return view('custom::customslider');
    }

    public function editAfter()
    {
        return view('custom::customslider');
    }
}