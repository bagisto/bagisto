<?php


return array(

	/**
	 * This property will be added to models being compiled with DbView
	 * to keep track of which field in the model is being compiled
	 */
	'model_property' => '__db_blade_compiler_content_field',

	/**
	 * The default model field to be compiled when not explicitly specified
	 * with DbView::field()
	 */
	'model_default_field' => 'content',

    'cache' => false
);
