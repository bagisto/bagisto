<?php

namespace Webkul\Ui\DataGrid\Helpers;

class Css extends AbstractFillable
{   
    const NO_RESULT = 'no-class';

    protected $datagrid = 'datagrid';
    protected $table = 'table';
    protected $thead = 'thead';
    protected $thead_td = 'thead_td';
    protected $tbody = 'tbody';
    protected $tbody_td = 'tbody_td';

    protected function setFillable(){
        $this->fillable = [
            'datagrid',
            'table',
            'thead',
            'thead_td',
            'tbody',
            'tbody_td',
        ];
    }

    public function __construct($args){
        parent::__construct($args);
    }
}
