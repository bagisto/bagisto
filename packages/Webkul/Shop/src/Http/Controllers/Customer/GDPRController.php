<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\GDPR\Repositories\GDPRDataRequestRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\DataGrids\GDPRRequestsDatagrid;
use Webkul\Shop\Http\Controllers\Controller;

class GDPRController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected GDPRDataRequestRepository $gdprDataRequestRepository,
        protected OrderRepository $orderRepository,
        protected CustomerAddressRepository $customerAddressRepository
    ) {
        if (app()->runningInConsole()) {
            return;
        }

        if (! core()->getConfigData('general.gdpr.settings.enabled')) {
            abort(404);
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(GDPRRequestsDatagrid::class)->process();
        }

        return view('shop::customers.account.gdpr.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $customer = auth()->guard('customer')->user();

        $params = request()->all() + [
            'status'        => 'pending',
            'customer_id'   => $customer->id,
            'customer_name' => $customer->first_name.' '.$customer->last_name,
            'email'         => $customer->email,
            'message'       => request()->get(request()->message),
        ];

        Event::dispatch('customer.account.gdpr-request.create.before');

        $gdprRequest = $this->gdprDataRequestRepository->create($params);

        Event::dispatch('customer.account.gdpr-request.create.after', $gdprRequest);

        Event::dispatch('customer.gdpr-request.create.after', $gdprRequest);

        session()->flash('success', trans('shop::app.customers.account.gdpr.create-success'));

        return redirect()->route('shop.customers.account.gdpr.index');
    }

    /**
     * Download the view in pdf form for the specified resource.
     */
    public function pdfView()
    {
        $customer = auth()->guard('customer')->user();

        try {
            $orders = $this->orderRepository->findWhere(['customer_id' => $customer->id])->toArray();

            $address = $this->customerAddressRepository->where('address_type', 'customer')->where('customer_id', $customer->id)->get()->toArray();

            $param = [
                'customerInformation' => $customer,
                'order'               => ! empty($orders) ? $orders : null,
                'address'             => ! empty($address) ? $address : null,
            ];

            if (is_null($param['order'])) {
                unset($param['order']);
            }

            if (is_null($param['address'])) {
                unset($param['address']);
            }
        } catch (\Exception $e) {
            $param = ['customerInformation' => $customer];
        }

        $pdf = \PDF::loadView('shop::customers.account.gdpr.pdf', compact('param'));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('customerInfo.pdf');
    }

    /**
     * Show the view in HTML format for the specified resource.
     */
    public function htmlView()
    {
        $customer = auth()->guard('customer')->user();

        try {
            $orders = $this->orderRepository->findWhere(['customer_id' => $customer->id])->toArray();

            $address = $this->customerAddressRepository->where('address_type', 'customer')->where('customer_id', $customer->id)->get();

            $param = [
                'customerInformation' => $customer,
                'order'               => ! empty($orders) ? $orders : null,
                'address'             => ! empty($address) ? $address : null,
            ];

            if (is_null($param['order'])) {
                unset($param['order']);
            }

            if (is_null($param['address'])) {
                unset($param['address']);
            }

        } catch (\Exception $e) {
            $param = ['customerInformation'=>$customer];
        }

        return view('shop::customers.account.gdpr.pdf', compact('param'));
    }

    /**
     * Cookie Consent.
     */
    public function cookieConsent()
    {
        return view('shop::components.layouts.cookie.consent');
    }

    /**
     * Revoke a GDPR request.
     */
    public function revoke($id)
    {
        $customer = auth()->guard('customer')->user();

        $data = $this->gdprDataRequestRepository->findWhere([
            'id'          => $id,
            'customer_id' => $customer->id,
            'status'      => 'pending',
        ])->first();

        if (! $data) {
            session()->flash('error', trans('shop::app.customers.account.gdpr.revoke-failed'));

            return redirect()->route('shop.customers.account.gdpr.index');
        }

        Event::dispatch('customer.account.gdpr-request.update.before');

        $gdprRequest = $this->gdprDataRequestRepository->update([
            'status'     => 'revoked',
            'revoked_at' => Carbon::now(),
        ], $id);

        Event::dispatch('customer.account.gdpr-request.update.after', $gdprRequest);

        Event::dispatch('customer.gdpr-request.update.after', $gdprRequest);

        session()->flash('success', trans('shop::app.customers.account.gdpr.revoked-successfully'));

        return redirect()->route('shop.customers.account.gdpr.index');
    }
}
