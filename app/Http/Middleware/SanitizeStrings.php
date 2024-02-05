<?php

namespace App\Http\Middleware;

use Webkul\Core\Http\Middleware\SanitizeStrings as Middleware;

class SanitizeStrings extends Middleware
{
    /**
     * The names of the attributes that should not be sanitized.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
