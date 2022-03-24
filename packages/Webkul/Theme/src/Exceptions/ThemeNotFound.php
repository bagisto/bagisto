<?php namespace Webkul\Theme\Exceptions;

class ThemeNotFound extends \Exception
{
	/**
	 * @param  string  $theme
	 * @return void
	 */
	public function __construct($themeName)
	{
		parent::__construct("Theme $themeName not Found", 1);
	}
}