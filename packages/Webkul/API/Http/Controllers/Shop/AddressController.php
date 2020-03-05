<?php

namespace Webkul\API\Http\Controllers\Shop;

use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\API\Http\Resources\Customer\CustomerAddress as CustomerAddressResource;

/**
 * Address controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerAddressRepository object
     *
     * @var \Webkul\Customer\Repositories\CustomerAddressRepository
     */
    protected $customerAddressRepository;

    /**
     * Controller instance
     *
     * @param  Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     */
    public function __construct(CustomerAddressRepository $customerAddressRepository)
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->middleware('auth:' . $this->guard);

        $this->_config = request('_config');

        $this->customerAddressRepository = $customerAddressRepository;
    }

    /**
     * Get user address.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $customer = auth($this->guard)->user();
        
        $addresses = $customer->addresses()->get();

        return CustomerAddressResource::collection($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $customer = auth($this->guard)->user();

        request()->merge([
            'address1'    => implode(PHP_EOL, array_filter(request()->input('address1'))),
            'customer_id' => $customer->id,
        ]);

        $this->validate(request(), [
            'address1' => 'string|required',
            'country'  => 'string|required',
            'state'    => 'string|required',
            'city'     => 'string|required',
            'postcode' => 'required',
            'phone'    => 'required',
        ]);

        $customerAddress = $this->customerAddressRepository->create(request()->all());

        return response()->json([
            'message' => 'Your address has been created successfully.',
            'data'    => new CustomerAddressResource($customerAddress),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $customer = auth($this->guard)->user();

        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $this->validate(request(), [
            'address1' => 'string|required',
            'country'  => 'string|required',
            'state'    => 'string|required',
            'city'     => 'string|required',
            'postcode' => 'required',
            'phone'    => 'required',
        ]);

        $this->customerAddressRepository->update(request()->all(), request()->input('id'));

        return response()->json([
            'message' => 'Your address has been updated successfully.',
            'data'    => new CustomerAddressResource($this->customerAddressRepository->find(request()->input('id'))),
        ]);
    }
}