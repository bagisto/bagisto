<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;

class AddressController extends Controller
{
    /**
     * Current customer.
     *
     * @var \Webkul\Customer\Models\Customer
     */
    protected $customer;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     * @return void
     */
    public function __construct(
        protected CustomerAddressRepository $customerAddressRepository
    ) {
    }

    /**
     * Address route index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = auth()->guard('customer')->user();

        return view('shop::customers.account.addresses.index')->with('addresses', $customer->addresses);
    }

    /**
     * Show the address create form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shop::customers.account.addresses.create', [
            'defaultCountry' => config('app.default_country'),
        ]);
    }

    /**
     * Create a new address for customer.
     *
     * @return view
     */
    public function store(AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        $requestData = [
            'company_name' => $request['company_name'],
            'first_name'   => $request['first_name'],
            'last_name'    => $request['last_name'],
            'vat_id'       => $request['vat_id'],
            'address1'     => $request['address1'],
            'country'      => $request['country'],
            'state'        => $request['state'],
            'city'         => $request['city'],
            'postcode'     => $request['postcode'],
            'phone'        => $request['phone'],
        ];

        Event::dispatch('customer.addresses.create.before');

        $customerAddress = $this->customerAddressRepository->create(array_merge($requestData, [
            'customer_id'     => $customer->id,
            'address1'        => implode(PHP_EOL, array_filter($requestData['address1'])),
            'default_address' => ! $customer->addresses->count(),
        ]));

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.create-success'));

        return redirect()->route('shop.customer.addresses.index');
    }

    /**
     * For editing the existing addresses of current logged in customer.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->id(),
        ]);

        $address['defaultCountry']  =  config('app.default_country');

        if (! $address) {
            abort(404);
        }

        return view('shop::customers.account.addresses.edit')->with('address', $address);
    }

    /**
     * Edit's the pre-made resource of customer called Address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer->addresses()->find($id)) {
            session()->flash('warning', trans('shop::app.security-warning'));

            return redirect()->route('shop.customer.addresses.index');
        }

        $requestData = [
            'company_name' => $request['company_name'],
            'first_name'   => $request['first_name'],
            'last_name'    => $request['last_name'],
            'vat_id'       => $request['vat_id'],
            'address1'     => $request['address1'],
            'country'      => $request['country'],
            'state'        => $request['state'],
            'city'         => $request['city'],
            'postcode'     => $request['postcode'],
            'phone'        => $request['phone'],
        ];

        Event::dispatch('customer.addresses.update.before', $id);

        $customerAddress = $this->customerAddressRepository->update(array_merge($requestData, [
            'address1' => implode(PHP_EOL, array_filter($requestData['address1'])),
        ]), $id);

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.edit-success'));

        return redirect()->route('shop.customer.addresses.index');
    }

    /**
     * To change the default address or make the default address,
     * by default when first address is created will be the default address.
     *
     * @return \Illuminate\Http\Response
     */
    public function makeDefault($id)
    {
        $customer = auth()->guard('customer')->user();

        if ($default = $customer->default_address) {
            $this->customerAddressRepository->find($default->id)->update(['default_address' => 0]);
        }

        if ($address = $this->customerAddressRepository->find($id)) {
            $address->update(['default_address' => 1]);
        } else {
            session()->flash('success', trans('shop::app.customers.account.addresses.default-delete'));
        }

        return redirect()->back();
    }

    /**
     * Delete address of the current customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = auth()->guard('customer')->user();

        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => $customer->id,
        ]);

        if (! $address) {
            abort(404);
        }

        Event::dispatch('customer.addresses.delete.before', $id);

        $this->customerAddressRepository->delete($id);

        Event::dispatch('customer.addresses.delete.after', $id);

        session()->flash('success', trans('shop::app.customers.account.addresses.delete-success'));

        return redirect()->route('shop.customer.addresses.index');
    }
}
