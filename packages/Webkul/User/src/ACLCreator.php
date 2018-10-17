<?php

namespace Webkul\User;

class ACLCreator {

	public $items = [];

	public $roles = [];

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
		$acl->items = $acl->sortItems($acl->items);

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
		$this->array_set($this->items, $children, $item);

		$this->roles[$route] = $key;
	}

	/**
	 * Method to sort through the acl items and put them in order
	 *
	 * @return void
	 */
	public function sortItems($items) {
		foreach ($items as &$item) {
			if(count($item['children'])) {
				$item['children'] = $this->sortItems($item['children']);
			}
		}

		usort($items, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return 0;
			}

			return ($a['sort'] < $b['sort']) ? -1 : 1;
		});

		return $items;
	}

	public function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
		$count = count($keys);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

		$finalKey = array_shift($keys);
		if(isset($array[$finalKey])) {
			$array[$finalKey] = $this->arrayMerge($array[$finalKey], $value);
		} else {
			$array[$finalKey] = $value;
		}

        return $array;
    }

	protected function arrayMerge(array &$array1, array &$array2)
	{
		$merged = $array1;
		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = $this->arrayMerge($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}
}