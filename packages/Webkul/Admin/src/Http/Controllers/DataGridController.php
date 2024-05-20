<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Webkul\DataGrid\Repositories\SavedFilterRepository;

class DataGridController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected SavedFilterRepository $savedFilterRepository)
    {
    }

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
            ->select([$column->options['params']['column']['label'].' as label', $column->options['params']['column']['value'].' as value'])
            ->where($column->options['params']['column']['label'], 'LIKE', '%'.$params['search'].'%')
            ->get();
    }

    /**
     * Save filters to the database.
     */
    public function store()
    {
        $userId = auth()->guard('admin')->user()->id;

        $this->validate(request(), [
            'name' => 'required|unique:saved_filters,name,NULL,id,src,'.request('src').',user_id,'.$userId,
        ]);

        $data = $this->savedFilterRepository->create(array_merge(request()->all(), ['user_id' => $userId]));

        return response()->json([
            'data'    => $data,
            'message' => trans('admin::app.components.datagrid.toolbar.filter.saved-success'),
        ], 200);
    }

    /**
     * Retrieves the saved filters.
     */
    public function get()
    {
        return $this->savedFilterRepository->findWhere([
            'src'     => request()->get('src'),
            'user_id' => auth()->guard('admin')->user()->id,
        ]);
    }

    /**
     * Delete the saved filter.
     */
    public function destroy(int $id)
    {
        $this->savedFilterRepository->deleteWhere([
            'id'      => $id,
            'user_id' => auth()->guard('admin')->user()->id,
        ]);

        return response()->json([
            'message' => trans('admin::app.components.datagrid.toolbar.filter.delete-success'),
        ], 200);
    }
}
