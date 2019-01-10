<?php

namespace Webkul\Ui\DataGrid\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * DataGrid controller
 *
 * @author    Nikhil Malik <nikhil@webkul.com> @ysmnikhil
 * &
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Pagination extends Paginator
{
    const LIMIT = 10;
    const OFFSET = 0;
    const VIEW = '';

    public function __construct(
        int $limit = null,
        int $offset = null,
        int $view = null
    ){
        $this->limit = $limit ?: self::LIMIT;
        $this->offset = $offset ?: self::OFFSET;
        $this->view = $view ?: self::VIEW;

        parent::__construct([

        ], 20);
    }
}
