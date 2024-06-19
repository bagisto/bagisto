<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;
use Webkul\Shop\Http\Resources\AddressResource;

class AddressController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerAddressRepository $customerAddressRepository) {}

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

        $data = array_merge($request->only([
            'company_name',
            'first_name',
            'last_name',
            'vat_id',
            'address',
            'country',
            'state',
            'city',
            'postcode',
            'phone',
            'default_address',
            'email',
        ]), [
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter($request->input('address'))),
        ]);

        $customerAddress = $this->customerAddressRepository->create($data);

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        return new JsonResource([
            'data'    => new AddressResource($customerAddress),
            'message' => trans('shop::app.customers.account.addresses.index.create-success'),
        ]);
    }

    /**
     * Update address for customer.
     */
    public function update(AddressRequest $request): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        Event::dispatch('customer.addresses.update.before');

        $customerAddress = $this->customerAddressRepository->update(array_merge($request->only([
            'company_name',
            'first_name',
            'last_name',
            'vat_id',
            'address',
            'country',
            'state',
            'city',
            'postcode',
            'phone',
            'default_address',
            'email',
        ]), [
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter(request()->input('address'))),
        ]), request('id'));

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        return new JsonResource([
            'data'    => new AddressResource($customerAddress),
            'message' => trans('shop::app.customers.account.addresses.index.update-success'),
        ]);
    }
}
