<?php

namespace Webkul\API\Http\Controllers\Shop;

class OrderController extends Controller
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
     * Repository instance.
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
     * Cancel customer's order.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $order = auth()->guard($this->guard)->user()->all_orders()->find($id);

        if ($order && $this->repository->cancel($order)) {
            return response()->json([
                'status'  => true,
                'message' => __('admin::app.response.cancel-success', [
                    'name' => 'Order'
                ]),
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => __('shop::app.common.error'),
        ]);
    }
}
