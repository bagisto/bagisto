<?php

namespace Webkul\Admin\Http\Controllers\DataGrid;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DataGrid\Repositories\SavedFilterRepository;

class SavedFilterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected SavedFilterRepository $savedFilterRepository)
    {
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

        $savedFilter = $this->savedFilterRepository->create(array_merge(
            request()->only([
                'name',
                'src',
                'applied',
            ]),
            ['user_id' => $userId]
        ));

        return response()->json([
            'data'    => $savedFilter,
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
     * Update the saved filter.
     */
    public function update(int $id)
    {
        $userId = auth()->guard('admin')->user()->id;

        $this->validate(request(), [
            'name' => 'required|unique:saved_filters,name,'.$id.',id,src,'.request('src').',user_id,'.$userId,
        ]);

        $updateFilter = $this->savedFilterRepository->update(request()->only([
            'name',
            'src',
            'applied',
        ]), $id);

        return response()->json([
            'data'    => $updateFilter,
            'message' => trans('admin::app.components.datagrid.toolbar.filter.updated-success'),
        ], 200);
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
