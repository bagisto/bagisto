<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Sanitizes the input recursively.
     */
    public function sanitizeArray(mixed $input): mixed
    {
        if (is_array($input)) {
            // Recursively sanitize array elements
            return array_map([$this, 'sanitizeArray'], $input);
        }

        // Sanitize string values
        return htmlspecialchars(
            str_replace(['(', ')'], ['&#x28;', '&#x29;'], preg_replace('/\{\{(.*?)\}\}/', '$1', (string) $input)),
            ENT_QUOTES, 'UTF-8'
        );
    }
}
