<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Shop\Http\Resources\AddressResource;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;
use Webkul\Customer\Repositories\CustomerAddressRepository;

class AddressController extends APIController
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerAddressRepository $customerAddressRepository
    ) {
    }

    /**
     * Customer addresses.
     */
    public function index(): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        return AddressResource::collection($customer->addresses);
    }

    /**
     * Create a new address for customer.
     */
    public function store(AddressRequest $request): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        Event::dispatch('customer.addresses.create.before');

        $address = [
            'company_name'    => $request->input('company_name'),
            'first_name'      => $request->input('first_name'),
            'last_name'       => $request->input('last_name'),
            'vat_id'          => $request->input('vat_id'),
            'address1'        => $request->input('address1'),
            'country'         => $request->input('country'),
            'state'           => $request->input('state'),
            'city'            => $request->input('city'),
            'postcode'        => $request->input('postcode'),
            'phone'           => $request->input('phone'),
            'customer_id'     => $customer->id,
            'address1'        => implode(PHP_EOL, array_filter($request->input('address1'))),
            'default_address' => ! $customer->addresses->count(),
        ];

        $customerAddress = $this->customerAddressRepository->updateOrCreate($address, $address);

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        return new JsonResource([
            'message' => trans('shop::app.customers.account.addresses.create-success')
        ]);
    }
}
