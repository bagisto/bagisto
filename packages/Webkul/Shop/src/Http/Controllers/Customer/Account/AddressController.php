<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;

class AddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerAddressRepository $customerAddressRepository) {}

    /**
     * Address route index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop::customers.account.addresses.index')->with('addresses', auth()->guard('customer')->user()->addresses);
    }

    /**
     * Show the address create form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shop::customers.account.addresses.create');
    }

    /**
     * Create a new address for customer.
     *
     * @return view
     */
    public function store(AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        Event::dispatch('customer.addresses.create.before');

        $data = array_merge(request()->only([
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
            'email',
            'default_address',
        ]), [
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter($request->input('address'))),
        ]);

        $customerAddress = $this->customerAddressRepository->create($data);

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.index.create-success'));

        return redirect()->route('shop.customers.account.addresses.index');
    }

    /**
     * For editing the existing addresses of current logged in customer.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->id(),
        ]);

        if (! $address) {
            abort(404);
        }

        return view('shop::customers.account.addresses.edit')->with('address', $address);
    }

    /**
     * Edit's the pre-made resource of customer called Address.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer->addresses()->find($id)) {
            session()->flash('warning', trans('shop::app.customers.account.addresses.index.security-warning'));

            return redirect()->route('shop.customers.account.addresses.index');
        }

        Event::dispatch('customer.addresses.update.before', $id);

        $data = array_merge(request()->only([
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
            'email',
        ]), [
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter($request->input('address'))),
        ]);

        $customerAddress = $this->customerAddressRepository->update($data, $id);

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.index.edit-success'));

        return redirect()->route('shop.customers.account.addresses.index');
    }

    /**
     * To change the default address or make the default address,
     * by default when first address is created will be the default address.
     *
     * @return \Illuminate\Http\Response
     */
    public function makeDefault(int $id)
    {
        $customer = auth()->guard('customer')->user();

        $defaultAddress = $customer->addresses()->where('default_address', 1)->first();

        $addressToSetDefault = $customer->addresses()->find($id);

        if ($defaultAddress && $defaultAddress->id !== $id) {
            $defaultAddress->update(['default_address' => 0]);
        }

        if ($addressToSetDefault) {
            $addressToSetDefault->update(['default_address' => 1]);
        } else {
            session()->flash('success', trans('shop::app.customers.account.addresses.index.default-delete'));
        }

        return redirect()->back();
    }

    /**
     * Delete address of the current customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if (! $address) {
            abort(404);
        }

        Event::dispatch('customer.addresses.delete.before', $id);

        $this->customerAddressRepository->delete($id);

        Event::dispatch('customer.addresses.delete.after', $id);

        session()->flash('success', trans('shop::app.customers.account.addresses.index.delete-success'));

        return redirect()->route('shop.customers.account.addresses.index');
    }
}
