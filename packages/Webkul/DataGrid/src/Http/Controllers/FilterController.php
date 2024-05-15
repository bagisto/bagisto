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
        $data = $this->savedFilterRepository->create(request()->all());

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
        return $this->savedFilterRepository->where('src', request()->get('src'))->get();
    }

    /**
     * Destroy a filter.
     */
    public function destroy()
    {
        $data = $this->savedFilterRepository->where('id', request()->get('id'))
            ->where('user_id', request()->get('user_id'))
            ->delete();

        return response()->json([
            'data'    => $data,
            'message' => trans('Filter has been deleted successfully.'),
        ], 200);
    }
}
