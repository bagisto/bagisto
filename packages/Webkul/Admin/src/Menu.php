<?php

namespace Webkul\Admin;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class Menu {

	protected $items;
	protected $current;
	protected $currentKey;

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
		$menu->sortItems();

		return $menu;
	}

	/**
	 * Add a menu item to the item stack
	 *
	 * @param string  $key  Dot seperated heirarchy
	 * @param string  $name Text for the anchor
	 * @param string  $url  URL for the anchor
	 * @param integer $sort Sorting index for the items
	 * @param string  $icon URL to use for the icon
	 */
	public function add($key, $name, $url, $sort = 0, $icon = null)
	{
		$item = [
			'key'		=> $key,
			'name'		=> $name,
			'url'		=> $url,
			'sort'		=> $sort,
			'icon'		=> $icon,
			'children'	=> []
        ];

		$children = str_replace('.', '.children.', $key);
		array_set($this->items, $children, $item);

		if ($url == $this->current) {
			$this->currentKey = $key;
		}
	}

	/**
	 * Method to sort through the menu items and put them in order
	 *
	 * @return void
	 */
	private function sortItems() {
		usort($this->items, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return 0;
			}

			return ($a['sort'] < $b['sort']) ? -1 : 1;
		});
	}

	/**
	 * Method to find the active links
	 *
	 * @param  array $item Item that needs to be checked if active
	 * @return string
	 */
	private function getActive($item)
	{
		$url = trim($item['url'], '/');

		if ($this->current === $url) {
			return 'active current';
		}

		if (strpos($this->currentKey, $item['key']) === 0) {
			return 'active';
		}
	}

}