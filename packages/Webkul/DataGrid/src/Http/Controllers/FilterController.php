<?php

namespace Webkul\DataGrid\Http\Controllers;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DataGrid\Repositories\SavedFilterRepository;

class FilterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected SavedFilterRepository $savedFilterRepository)
    {
    }

    /**
     * Store a new filter in storage.
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
            'message' => trans('Filter has been saved successfully.'),
        ], 200);
    }

    /**
     * Get filters.
     */
    public function get()
    {
        return $this->savedFilterRepository->findWhere([
            'src'     => request()->get('src'),
            'user_id' => auth()->guard('admin')->user()->id,
        ]);
    }

    /**
     * Destroy a filter.
     */
    public function destroy(int $id)
    {
        $this->savedFilterRepository->deleteWhere([
            'id'      => $id,
            'user_id' => auth()->guard('admin')->user()->id,
        ]);

        return response()->json([
            'message' => trans('Filter has been deleted successfully.'),
        ], 200);
    }
}
