<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\GDPR\Repositories\GDPRDataRequestRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\DataGrids\GDPRRequestsDatagrid;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Mail\Customer\Gdpr\DeleteRequestMail;
use Webkul\Shop\Mail\Customer\Gdpr\UpdateRequestMail;

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
    ) {}

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

        $data = $this->gdprDataRequestRepository->create($params);

        if ($data) {
            $mailClass = $params['type'] == 'update' ? UpdateRequestMail::class : DeleteRequestMail::class;

            try {
                Mail::queue(new $mailClass($params));

                session()->flash('success', trans('shop::app.customers.account.gdpr.success-verify'));
            } catch (\Exception $e) {
                session()->flash('warning', trans('shop::app.customers.account.gdpr.success-verify-email-unsent'));
            }
        } else {
            session()->flash('error', trans('shop::app.customers.account.gdpr.unable-to-sent'));
        }

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
}
