<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Rules\VatIdRule;
use Auth;

/**
 * Customer controlller for the customer basically for the tasks of customers which will
 * be done after customer authenticastion.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerAddressRepository object
     *
     * @param \Webkul\Customer\Repositories\CustomerAddressRepository $customerAddressRepository
     *
     * @var Object
     */
    protected $customerAddressRepository;

    public function __construct(CustomerAddressRepository $customerAddressRepository)
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customerAddressRepository = $customerAddressRepository;

        $this->customer = auth()->guard('customer')->user();
    }

    /**
     * Address Route index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view'])->with('addresses', $this->customer->addresses);
    }

    /**
     * Show the address create form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view'], [
            'defaultCountry' => config('app.default_country'),
        ]);
    }

    /**
     * Create a new address for customer.
     *
     * @return view
     */
    public function store()
    {
        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $data = collect(request()->input())->except('_token')->toArray();

        $this->validate(request(), [
            'company_name' => 'string',
            'address1'     => 'string|required',
            'country'      => 'string|required',
            'state'        => 'string|required',
            'city'         => 'string|required',
            'postcode'     => 'required',
            'phone'        => 'required',
            'vat_id'       => new VatIdRule(),
        ]);

        $cust_id['customer_id'] = $this->customer->id;
        $data = array_merge($cust_id, $data);

        if ($this->customer->addresses->count() == 0) {
            $data['default_address'] = 1;
        }

        if ($this->customerAddressRepository->create($data)) {
            session()->flash('success', trans('shop::app.customer.account.address.create.success'));

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('shop::app.customer.account.address.create.error'));

            return redirect()->back();
        }
    }

    /**
     * For editing the existing addresses of current logged in customer
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if (! $address) {
            abort(404);
        }

        return view($this->_config['view'], array_merge(
            compact('address'),
            ['defaultCountry' => config('app.default_country')]
        ));
    }

    /**
     * Edit's the premade resource of customer called
     * Address.
     *
     * @return redirect
     */
    public function update($id)
    {
        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $this->validate(request(), [
            'company_name' => 'string',
            'address1'     => 'string|required',
            'country'      => 'string|required',
            'state'        => 'string|required',
            'city'         => 'string|required',
            'postcode'     => 'required',
            'phone'        => 'required',
            'vat_id'       => new VatIdRule(),
        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        $addresses = $this->customer->addresses;

        foreach ($addresses as $address) {
            if ($id == $address->id) {
                session()->flash('success', trans('shop::app.customer.account.address.edit.success'));

                $this->customerAddressRepository->update($data, $id);

                return redirect()->route('customer.address.index');
            }
        }

        session()->flash('warning', trans('shop::app.security-warning'));

        return redirect()->route('customer.address.index');
    }

    /**
     * To change the default address or make the default address, by default when first address is
     * created will be the default address
     *
     * @return Response
     */
    public function makeDefault($id)
    {
        if ($default = $this->customer->default_address)
            $this->customerAddressRepository->find($default->id)->update(['default_address' => 0]);

        if ($address = $this->customerAddressRepository->find($id)) {
            $address->update(['default_address' => 1]);
        } else {
            session()->flash('success', trans('shop::app.customer.account.address.index.default-delete'));
        }

        return redirect()->back();
    }

    /**
     * Delete address of the current customer
     *
     * @param integer $id
     *
     * @return response mixed
     */
    public function destroy($id)
    {
        $address = $this->customerAddressRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if (! $address)
            abort(404);

        $this->customerAddressRepository->delete($id);

        session()->flash('success', trans('shop::app.customer.account.address.delete.success'));

        return redirect()->route('customer.address.index');
    }
}
