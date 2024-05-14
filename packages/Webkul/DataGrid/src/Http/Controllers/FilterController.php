<?php

namespace Webkul\DataGrid\Http\Controllers;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DataGrid\Repositories\FilterRepository;

class FilterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected FilterRepository $filterRepository)
    {
    }

    /**
     * Store a new filter in storage.
     */
    public function store()
    {
        return $this->filterRepository->create(request()->all());
    }

    /**
     * Get filters.
     */
    public function get()
    {
        return $this->filterRepository->where('src', request()->get('src'))->get();
    }
}
