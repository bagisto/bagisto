Event::listen('attributedatagrid.before.add.column.id', function($dataGridInstance) {
    $data = [
        'index' => 'is_required',
        'label' => 'trans',
        'type' => 'boolean',
        'sortable' => true,
        'searchable' => false,
        'wrapper' => function ($value) {
            if ($value->is_required == 1)
                return 'True';
            else
                return 'False';
        }
    ];

    $dataGridInstance->addColumn($data);
});