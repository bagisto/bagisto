<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AddressRequest;
use Webkul\Admin\Http\Resources\AddressResource;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class AddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerAddressRepository $customerAddressRepository
    ) {}

    /**
     * Fetch address by customer id.
     *
     * @return \Illuminate\View\View
     */
    public function index(int $id)
    {
        $customer = $this->customerRepository->find($id);

        return view('admin::customers.addresses.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(int $id)
    {
        $customer = $this->customerRepository->find($id);

        return view('admin::customers.addresses.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $id, AddressRequest $request): JsonResponse
    {
        $data = array_merge($request->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address',
            'city',
            'country',
            'state',
            'postcode',
            'phone',
            'email',
            'default_address',
        ]), [
            'address' => implode(PHP_EOL, array_filter(request()->input('address'))),
        ]);

        Event::dispatch('customer.addresses.create.before');

        $address = $this->customerAddressRepository->create(array_merge($data, [
            'customer_id' => $id,
        ]));

        Event::dispatch('customer.addresses.create.after', $address);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.create-success'),
            'data'    => new AddressResource($address),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $address = $this->customerAddressRepository->find($id);

        return view('admin::customers.addresses.edit', compact('address'));
    }

    /**
     * Edit's the pre made resource of customer called address.
     */
    public function update(int $id, AddressRequest $request): JsonResponse
    {
        $data = array_merge($request->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address',
            'city',
            'country',
            'state',
            'postcode',
            'phone',
            'email',
            'default_address',
        ]), [
            'address' => implode(PHP_EOL, array_filter(request()->input('address'))),
        ]);

        Event::dispatch('customer.addresses.update.before', $id);

        $address = $this->customerAddressRepository->update($data, $id);

        Event::dispatch('customer.addresses.update.after', $address);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.update-success'),
            'data'    => new AddressResource($address),
        ]);
    }

    /**
     * To change the default address or make the default address,
     * by default when first address is created will be the default address.
     *
     * @return \Illuminate\Http\Response
     */
    public function makeDefault($id)
    {
        if ($default = $this->customerAddressRepository->findOneWhere(['customer_id' => $id, 'default_address' => 1])) {
            $default->update(['default_address' => 0]);
        }

        $address = $this->customerAddressRepository->findOneWhere([
            'id'              => request('set_as_default'),
            'customer_id'     => $id,
        ]);

        $address->update(['default_address' => 1]);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.set-default-success'),
            'data'    => $address,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Event::dispatch('customer.addresses.delete.before', $id);

        $this->customerAddressRepository->delete($id);

        Event::dispatch('customer.addresses.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.address-delete-success'),
        ]);
    }
}
