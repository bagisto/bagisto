<?php

namespace Webkul\Core\Helpers;

class Session
{
    public static function flashError($trans)
    {
        session()->flash('error', trans($trans));
    }
}