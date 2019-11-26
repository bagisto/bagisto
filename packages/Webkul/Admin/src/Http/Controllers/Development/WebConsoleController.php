<?php

namespace Webkul\Admin\Http\Controllers\Development;

use Alkhachatryan\LaravelWebConsole\LaravelWebConsole;
use Webkul\Admin\Http\Controllers\Controller;

/**
 * WebConsole controller
 *
 * @author    Alexey Khachatryan <info@khachatryan.org>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class WebConsoleController extends Controller
{
    /**
     * Show the Web Console.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(){
        return LaravelWebConsole::show();
    }
}