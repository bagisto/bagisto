<?php

namespace Webkul\User;

class ACLCreator {

	public $items = [];

	/**
	 * Shortcut method for create a acl with a callback.
	 * This will allow you to do things like fire an event on creation.
	 *
	 * @param  callable $callback Callback to use after the acl creation
	 * @return object
	 */
	public static function create($callback) {
		$acl = new ACLCreator();
		$callback($acl);
		$acl->sortItems($acl->items);

		return $acl;
	}

	/**
	 * Add a acl item to the item stack
	 *
	 * @param string  $key   Dot seperated heirarchy
	 * @param string  $name  Text for the anchor
	 * @param string  $route Route for the acl
	 * @param integer $sort  Sorting index for the items
	 */
	public function add($key, $name, $route, $sort = 0)
	{
		$item = [
			'key'		 => $key,
			'name'		 => $name,
			'route'		 => $route,
			'sort'		 => $sort,
			'children'	 => []
        ];

		$children = str_replace('.', '.children.', $key);
		array_set($this->items, $children, $item);
	}

	/**
	 * Method to sort through the acl items and put them in order
	 *
	 * @return void
	 */
	public function sortItems($items) {
		usort($items, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return 0;
			}

			return ($a['sort'] < $b['sort']) ? -1 : 1;
		});

		return $items;
	}
}