<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\View\View;

class HelpController extends Controller
{
    /**
     * Display the help & resources page.
     */
    public function index(): View
    {
        return view('admin::help.index');
    }
}
