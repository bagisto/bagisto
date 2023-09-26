<?php

namespace Webkul\Shop\Http\Controllers;

class DataGridController extends Controller
{
    /**
     * Look up.
     */
    public function lookUp()
    {
        $params = $this->validate(request(), [
            'search' => ['required', 'min:2'],

            'repository' => ['required'],

            'column'       => ['required', 'array'],
            'column.label' => ['required'],
            'column.value' => ['required'],
        ]);

        return app($params['repository'])
            ->select([$params['column']['label'] . ' as label', $params['column']['value'] . ' as value'])
            ->where($params['column']['label'], 'LIKE', '%' . $params['search'] . '%')
            ->get();
    }
}
