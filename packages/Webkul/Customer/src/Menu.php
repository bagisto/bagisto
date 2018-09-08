<?php

namespace Webkul\Customer;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class Menu
{
    public $items = array();
    public $current;
	public $currentKey;

	public function __construct() {
		$this->current = Request::url();
	}


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

    /**
	 * Method to find the active links
	 *
	 * @param  array $item Item that
     * needs to be checked if active
	 * @return string
	 */
	public function getActive($item)
	{
		$url = trim($item['url'], '/');
		if ((strpos($this->current, $url) !== false) || (strpos($this->currentKey, $item['key']) === 0)) {
			return 'active';
		}
	}
}
