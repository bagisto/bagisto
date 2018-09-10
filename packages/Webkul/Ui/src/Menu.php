<?php

namespace Webkul\Ui;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class Menu {

	public $items;
	public $current;
	public $currentKey;

	public function __construct() {
		$this->current = Request::url();
	}

	/**
	 * Shortcut method for create a menu with a callback.
	 * This will allow you to do things like fire an event on creation.
	 *
	 * @param  callable $callback Callback to use after the menu creation
	 * @return object
	 */
	public static function create($callback) {
		$menu = new Menu();
		$callback($menu);
		$menu->sortItems($menu->items);

		return $menu;
	}

	/**
	 * Add a menu item to the item stack
	 *
	 * @param string  $key   Dot seperated heirarchy
	 * @param string  $name  Text for the anchor
	 * @param string  $route Route for the menu
	 * @param integer $sort  Sorting index for the items
	 * @param string  $iconClass Icon Class name
	 */
	public function add($key, $name, $route, $sort = 0, $iconClass = null)
	{
		$url = route($route);
		$item = [
			'key'		 => $key,
			'name'		 => $name,
			'url'		 => $url,
			'route'		 => $route,
			'sort'		 => $sort,
			'icon-class' => $iconClass,
			'children'	 => []
        ];

		if (strpos($this->current, $url) !== false) {
			$this->currentKey = $key;
		}

		$children = str_replace('.', '.children.', $key);
		array_set($this->items, $children, $item);
	}

	/**
	 * Method to sort through the menu items and put them in order
	 *
	 * @return void
	 */
	public function sortItems($items) {
		if(!$items) {
			return;
		}
		usort($items, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return 0;
			}

			return ($a['sort'] < $b['sort']) ? -1 : 1;
		});

		return $items;
	}

	/**
	 * Method to find the active links
	 *
	 * @param  array $item Item that needs to be checked if active
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