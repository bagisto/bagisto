<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * DataGrid controller
 *
 * @author    Nikhil Malik <nikhil@webkul.com> @ysmnikhil
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class DataGridController extends Controller
{
    public function massDelete() {
        dd(request()->all());
    }

    public function massUpdate() {
        dd(request()->all());
    }
}
