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
        return $this->savedFilterRepository->create(request()->all());
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
        return $this->savedFilterRepository->delete(request()->get('src'));
    }
}
