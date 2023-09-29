<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Crypt;

class DataGridController extends Controller
{
    /**
     * Look up.
     */
    public function lookUp()
    {
        /**
         * Validation for parameters.
         */
        $params = $this->validate(request(), [
            'datagrid_id' => ['required'],
            'column'      => ['required'],
            'search'      => ['required', 'min:2'],
        ]);

        /**
         * Preparing the datagrid instance and only columns.
         */
        $datagrid = app(Crypt::decryptString($params['datagrid_id']));
        $datagrid->prepareColumns();

        /**
         * Finding the first column from the collection.
         */
        $column = collect($datagrid->getColumns())->where('index', $params['column'])->firstOrFail();

        /**
         * Fetching on the basis of column options.
         */
        return app($column->options['params']['repository'])
            ->select([$column->options['params']['column']['label'] . ' as label', $column->options['params']['column']['value'] . ' as value'])
            ->where($column->options['params']['column']['label'], 'LIKE', '%' . $params['search'] . '%')
            ->get();
    }
}
