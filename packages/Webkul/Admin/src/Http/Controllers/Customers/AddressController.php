<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Rules\AlphaNumericSpace;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Customer\Rules\VatIdRule;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;

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
    )
    {
    }

    /**
     * Fetch address by customer id.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $customer = $this->customerRepository->find($id);

        return view('admin::customers.addresses.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $customer = $this->customerRepository->find($id);

        return view('admin::customers.addresses.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'company_name' => [new AlphaNumericSpace],
            'address1'     => ['required', 'array'],
            'country'      => ['required', new AlphaNumericSpace],
            'state'        => ['required', new AlphaNumericSpace],
            'city'         => ['required', 'string'],
            'postcode'     => ['required', 'numeric'],
            'phone'        => ['required', new PhoneNumber],
            'vat_id'       => [new VatIdRule()],
        ]);

        $data = array_merge(request()->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address1',
            'city',
            'country',
            'state',
            'postcode',
            'phone',
            'default_address',
        ]), [
            'address1' => implode(PHP_EOL, array_filter(request()->input('address1'))),
            'address2' => implode(PHP_EOL, array_filter(request()->input('address2', []))),
        ]);

        Event::dispatch('customer.addresses.create.before');

        $customerAddress = $this->customerAddressRepository->create($data);

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        return new JsonResponse([
            'message' => trans('admin::app.customers.addresses.create-success'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $address = $this->customerAddressRepository->find($id);

        return view('admin::customers.addresses.edit', compact('address'));
    }

    /**
     * Edit's the pre made resource of customer called address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id): JsonResponse
    {
        $this->validate(request(), [
            'company_name' => [new AlphaNumericSpace],
            'address1'     => ['required', 'array'],
            'country'      => ['required', new AlphaNumericSpace],
            'state'        => ['required', new AlphaNumericSpace],
            'city'         => ['required', 'string'],
            'postcode'     => ['required', 'numeric'],
            'phone'        => ['required', new PhoneNumber],
            'vat_id'       => [new VatIdRule()],
        ]);

        $data = array_merge(request()->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address1',
            'city',
            'country',
            'state',
            'postcode',
            'phone',
            'default_address',
        ]), [
            'address1' => implode(PHP_EOL, array_filter(request()->input('address1'))),
            'address2' => implode(PHP_EOL, array_filter(request()->input('address2', []))),
        ]);

        Event::dispatch('customer.addresses.update.before', $id);

        $customerAddress = $this->customerAddressRepository->update($data, $id);

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        return new JsonResponse([
            'message' => trans('admin::app.customers.addresses.update-success'),
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
            'customer_id'     => $id
        ]);

        if ($address) {
            $address->update(['default_address' => 1]);

            session()->flash('success', trans('admin::app.customers.customers.view.set-default-success'));
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::dispatch('customer.addresses.delete.before', $id);

        $this->customerAddressRepository->delete($id);

        Event::dispatch('customer.addresses.delete.after', $id);

        session()->flash('success', trans('admin::app.customers.customers.view.address-delete-success'));

        return redirect()->back();
    }
}
