<?php

namespace Webkul\API\Http\Controllers\Shop;

class TransactionController extends Controller
{
    /**
     * Contains current guard.
     *
     * @var array
     */
    protected $guard;

    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Repository object.
     *
     * @var \Webkul\Core\Eloquent\Repository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');

        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {

            auth()->setDefaultDriver($this->guard);

            $this->middleware('auth:' . $this->guard);
        }

        if ($this->_config) {
            $this->repository = app($this->_config['repository']);
        }
    }

    /**
     * Returns a listing of the Order Transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->repository->scopeQuery(function($query) {
            $query = $query->leftJoin('orders', 'order_transactions.order_id', '=', 'orders.id')->select('order_transactions.*', 'orders.customer_id');

            if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {
                $query = $query->where('customer_id', auth()->user()->id);
            }

            foreach (request()->except(['page', 'limit', 'pagination', 'sort', 'order', 'token']) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if ($sort = request()->input('sort')) {
                $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            $results = $query->paginate(request()->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->_config['resource']::collection($results);
    }

    /**
     * Returns an individual invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {
            $query = $this->repository->leftJoin('orders', 'order_transactions.order_id', '=', 'orders.id')
                          ->select('order_transactions.*', 'orders.customer_id')
                          ->where('customer_id', auth()->user()->id)
                          ->findOrFail($id);
        } else {
            $query = $this->repository->findOrFail($id);
        }

        return new $this->_config['resource']($query);
    }
}
