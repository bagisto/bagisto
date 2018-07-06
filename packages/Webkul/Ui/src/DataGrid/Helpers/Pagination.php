<?php

namespace Webkul\Ui\DataGrid\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Pagination extends Paginator
{   
    const LIMIT = 20;
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
