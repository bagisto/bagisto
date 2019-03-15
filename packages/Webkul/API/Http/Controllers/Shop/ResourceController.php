<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Resource Controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ResourceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Repository object
     *
     * @var array
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api');

        $this->_config = request('_config');

        $this->repository = app($this->_config['repository']);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->repository->scopeQuery(function($query) {
            foreach (request()->except(['page', 'limit', 'pagination', 'sort', 'order']) as $input => $value) {
                $query = $query->where($input, $value);
            }

            if ($sort = request()->input('sort')) {
                $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
            }

            return $query;
        });

        if (! is_null(request()->input('pagination')) && request()->input('pagination')) {
            $results = $query->paginate(request()->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->_config['resource']::collection($results);
    }

    /**
     * Returns a individual resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        return new $this->_config['resource'](
                $this->repository->findOrFail($id)
            );
    }
}
