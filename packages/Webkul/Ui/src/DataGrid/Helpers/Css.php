<?php

namespace Webkul\Ui\DataGrid\Helpers;

/**
 * Css
 * @author    Nikhil Malik <nikhil@webkul.com> @ysmnikhil
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Css extends AbstractFillable
{
    const NO_RESULT = 'no-class';

    protected $datagrid = 'datagrid';
    protected $table = 'table';
    protected $thead = 'thead';
    protected $thead_td = 'thead_td';
    protected $tbody = 'tbody';
    protected $tbody_td = 'tbody_td';

    public function __construct($args) {
        parent::__construct($args);
    }

    protected function setFillable() {
        $this->fillable = [
            'datagrid',
            'table',
            'thead',
            'thead_td',
            'tbody',
            'tbody_td',
        ];
    }
}
