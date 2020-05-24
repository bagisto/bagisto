<?php 

namespace Webkul\Theme\Exceptions;

class ThemeAlreadyExists extends \Exception
{
	/**
	 * @param  \Webkul\Theme\Theme  $theme
	 * @return void
	 */
	public function __construct($theme)
	{
		parent::__construct("Theme {$theme->name} already exists", 1);
	}
}