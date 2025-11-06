<?php

namespace Webkul\Stripe\Http\Controllers;

use Stripe\Stripe;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Stripe\Repositories\StripeCartRepository as StripeCart;

class RefundController extends Controller
{
    /**
     * To hold the Test stripe secret key
     */
    protected $stripeSecretKey = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected RefundRepository $refundRepository,
        protected StripeCart $stripeCart
    ) {
        $testMode = (bool) core()->getConfigData('sales.payment_methods.stripe.debug');

        if ($testMode) {
            $this->stripeSecretKey = core()->getConfigData('sales.payment_methods.stripe.api_test_key');
        } else {
            $this->stripeSecretKey = core()->getConfigData('sales.payment_methods.stripe.api_key');
        }

        Stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\View
     */
    public function create($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        return view('stripe::admin.sales.refunds.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function store($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.create.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items'   => 'array',
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        if (! isset($data['refund']['shipping'])) {
            $data['refund']['shipping'] = 0;
        }

        $totals = $this->refundRepository->getOrderItemsRefundSummary($data['refund'], $orderId);

        if (! $totals) {
            session()->flash('error', trans('admin::app.sales.refunds.create.invalid-qty'));

            return redirect()->route('admin.sales.refunds.index');
        }

        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'] + $data['refund']['shipping'] + $data['refund']['adjustment_refund'] - $data['refund']['adjustment_fee'];

        $isStripeToken = $this->stripeCart->scopeQuery(function ($query) {
            return $query->orderBy('id', 'desc');
        })->findByField('cart_id', $order->cart_id)->first();

        if (isset($isStripeToken)) {

            $stripeToken = $isStripeToken->stripe_token;

            $decodeStripeToken = json_decode($stripeToken);

            $paymentIntentId = $decodeStripeToken->paymentIntent->id;

            try {
                $result = \Stripe\Refund::create([
                    'payment_intent' => $paymentIntentId,
                    'amount'         => round($refundAmount, 2) * 100,
                ]);
            } catch (\Stripe\Exception\CardException $e) {

                // Since it's a decline, \Stripe\Exception\CardException will be caught
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (\Stripe\Exception\RateLimitException $e) {

                // Too many requests made to the API too quickly
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (\Stripe\Exception\InvalidRequestException $e) {

                // Invalid parameters were supplied to Stripe's API
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (\Stripe\Exception\AuthenticationException $e) {

                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (\Stripe\Exception\ApiConnectionException $e) {

                // Network communication with Stripe failed
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (\Stripe\Exception\ApiErrorException $e) {

                // Display a very generic error to the user, and maybe send
                // yourself an email
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            } catch (Exception $e) {

                // Something else happened, completely unrelated to Stripe
                session()->flash('error', $e->getError()->message);

                return redirect()->back();
            }

            if ($result['status'] != 'succeeded') {
                session()->flash('error', trans('admin::app.sales.refunds.creation-error'));

                return redirect()->back();
            }
        }

        if (! $refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.refund-limit-error', [
                'amount' => core()->formatBasePrice($maxRefundAmount),
            ]));

            return redirect()->back();
        }

        $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('admin::app.sales.refunds.create.create-success'));

        return redirect()->route('admin.sales.refunds.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQty($orderId)
    {
        $refundSummary = $this->refundRepository->getOrderItemsRefundSummary(request()->all(), $orderId);

        if (! $refundSummary) {
            return response('');
        }

        return response()->json($refundSummary);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function view($id)
    {
        $refund = $this->refundRepository->findOrFail($id);

        return view('admin::sales.refunds.view', compact('refund'));
    }
}
