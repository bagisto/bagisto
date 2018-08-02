<?php

namespace Webkul\Customer;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class Menu
{
    public $items = array();

    public static function create($callback)
    {
        $menu = new Menu();
        $callback($menu);
        return $menu;
    }

    public function add($route, $name)
    {
        $url = route($route);
        $item = [
            'name' => $name,
            'url' => $url,
        ];
        array_push($this->items, $item);
    }
}
