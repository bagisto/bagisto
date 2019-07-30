<?php

namespace Webkul\StripeConnect\Http\Controllers;

use Webkul\StripeConnect\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\StripeConnect\Repositories\StripeCartRepository as StripeCart;
use Webkul\StripeConnect\Repositories\StripeConnectRepository as StripeConnect;
use Stripe\Stripe as Stripe;
use Stripe\Card as StripeCard;
use Stripe\Token as StripeToken;
use Stripe\Charge as StripeCharge;
use Stripe\Refund as StripeRefund;
use Stripe\Invoice as StripeInvoice;
use Stripe\Customer as StripeCustomer;
use Stripe\InvoiceItem as StripeInvoiceItem;

/**
 * StripeConnect Controller
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class StripeConnectController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * StripeRepository object
     *
     * @var array
     */
    protected $stripeRepository;

    /**
     * To hold the live stripe publishable key
     */
    protected $stripeLivePublishableKey = null;

    /**
     * To hold the live stripe secret key
     */
    protected $stripeLiveSecretKey = null;

    /**
     * To hold the Test stripe publishable key
     */
    protected $stripeTestPublishableKey = null;

    /**
     * To hold the Test stripe secret key
     */
    protected $stripeTestSecretKey = null;

    /**
     * Determine test mode
     */
    protected $testMode;

    /**
     * Determine if Stripe is active or Not
     */
    protected $active;

    /**
     * Statement descriptor string
     */
    protected $statementDescriptor;

    /**
     * Stripe Cart Repository Instance holder
     */
    protected $stripeCart;

    /**
     * Stripe Connect Repository Instance holder
     */
    protected $stripeConnect;

    /**
     * Stripe commission beared
     */
    protected $stripeFeeBearer;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        StripeCart $stripeCart,
        StripeConnect $stripeConnect
    )
    {
        // $this->middleware('auth:super-admin', ['only' => ['storeDetails', 'createDetails', 'editDetails', 'updateDetails']]);

        $this->orderRepository = $orderRepository;

        $this->stripeCart = $stripeCart;

        $this->stripeConnect = $stripeConnect;

        $this->testMode = env('STRIPE_ENABLE_TESTING');

        $this->stripeTestPublishableKey = env('STRIPE_TEST_PUBLISHABLE_KEY');

        $this->stripeTestSecretKey = env('STRIPE_TEST_SECRET_KEY');

        $this->stripeLivePublishableKey = env('STRIPE_LIVE_PUBLISHABLE_KEY');

        $this->stripeLiveSecretKey = env('STRIPE_LIVE_SECRET_KEY');

        $this->stripeFeeBearer = $this->stripeFeeBearer;

        if (config('stripe.connect.details.statementdescriptor')) {
            $this->statementDescriptor = config('stripe.connect.details.statementdescriptor');
        } else {
            $this->statementDescriptor = env('STRIPE_STATEMENT_DESCRIPTOR');
        }
    }

    public function collectToken()
    {
        $data = request()->all();

        if (auth()->guard('customer')->check()) {
            //customer authenticated but using saved card
            if(isset($data['useSavedCard'])) {
                // session()->put('stripe_card', $data);
                $this->stripeCart->create([
                    'cart_id' => \Cart::getCart()->id,
                    'stripe_token' => json_encode($data)
                ]);

                return response()->json(['success' => 'true']);
                //customer authenticated but not using saved card
            } else {
                $misc = request()->input('stripeReturn');

                //customer authenticated but opt to remember card
                $last4 = null;
                if (isset($data['last4'])) {
                    $stripeToken = $data['stripeToken'];
                    $last4 = $data['last4'];

                    $result = $this->stripeRepository->create([
                        'customer_id' => auth()->guard('customer')->user()->id,
                        'token' => $stripeToken,
                        'last_four' => $last4,
                        'misc' => json_encode($misc)
                    ]);

                    // session()->put(['stripe_card' => $result]);
                    $this->stripeCart->create([
                        'cart_id' => \Cart::getCart()->id,
                        'stripe_token' => json_encode($data)
                    ]);

                    if ($result) {
                        return response()->json(['success' => 'true']);
                    } else {
                        return response()->json(['success' => 'false'], 400);
                    }

                  //customer authenticated but not opting for remembering card
                } else {
                    $stripeCart = $this->stripeCart->findWhere([
                        'cart_id' => Cart::getCart()->id
                    ]);

                    if ($stripeCart->count() == 0) {
                        $this->stripeCart->create([
                            'cart_id' => \Cart::getCart()->id,
                            'stripe_token' => json_encode($data)
                        ]);
                    } else {
                        $stripeCart->first()->update([
                            'stripe_token' => json_encode($data)
                        ]);
                    }
                }

                return response()->json(['success' => 'true']);
            }
        } else {
            //customer not authenticated
            $stripeCart = $this->stripeCart->findWhere([
                'cart_id' => Cart::getCart()->id
            ]);

            if ($stripeCart->count() == 0) {
                $this->stripeCart->create([
                    'cart_id' => \Cart::getCart()->id,
                    'stripe_token' => json_encode($data)
                ]);
            } else {
                $stripeCart->first()->update([
                    'stripe_token' => json_encode($data)
                ]);
            }


            return response()->json(['success' => 'true']);
        }
    }

    public function createCharge()
    {
        $result = $this->getCharge();

        $this->stripeCart->deleteWhere([
            'cart_id' => \Cart::getCart()->id
        ]);

        if ($result) {
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            Cart::deActivateCart();

            session()->flash('order', $order);

            return redirect()->route('shop.checkout.success');
        } else {
            session()->flash('error', trans('stripe::app.payment-failed'));

            return redirect()->route('shop.home.index');
        }

        return redirect()->route('shop.home.index');
    }

    public function deleteCard()
    {
        $deleteIfFound = $this->stripeRepository->findWhere([
            'id' => request()->input('id'),
            'customer_id' => auth()->guard('customer')->user()->id]);

        $result = $deleteIfFound->first()->delete();

        return (string)$result;
    }

    public function getCharge()
    {
        $stripeConnect = $this->stripeConnect->findWhere([
            'company_id' => \Company::getCurrent()->id
        ]);

        if ($stripeConnect->count()) {
            $sellerUserId = $stripeConnect->first()->stripe_user_id;
        } else {
            session()->flash('warning', trans('stripe::app.stripe-unavailable'));

            return redirect()->route('shop.checkout.success');
        }

        if($this->testMode) {
            Stripe::setApiKey($this->stripeTestSecretKey);
        } else {
            Stripe::setApiKey($this->stripeLiveSecretKey);
        }

        $stripeCard = $this->stripeCart->findWhere([
                        'cart_id' => Cart::getCart()->id
                    ])->first()->stripe_token;

        $stripeCard = json_decode($stripeCard);

        if (isset($stripeCard->stripeToken)) {
            $stripeToken = $stripeCard->stripeToken;
        } else if (isset($stripeCard->useSavedCard)) {
            $cardId = $stripeCard->savedCardId;

            $card = $this->stripeRepository->findOneByField('id', $cardId);

            if ($card->need_new_token) {
                $result = false;
            } else {
                $stripeToken = $card->token;

                $this->stripeRepository->update(['need_new_token' => 1], $cardId);
            }
        } else {
            $stripeToken = $stripeCard->stripeToken;
        }

        $cart = Cart::getCart();

        try {
            if ($this->stripeFeeBearer == "seller" || $this->stripeFeeBearer == null) {
                $baseGrandTotal = $cart->base_grand_total;

                // admin or owner commission
                $ownerCommissionRate = env('STRIPE_ADMIN_COMMISSION') ?? 0.0;
                $ownerCommission = ($baseGrandTotal * $ownerCommissionRate) / 100;

                $applicationFee = round($ownerCommission, 2);

                $result = StripeCharge::create([
                    "amount" => round(Cart::getCart()->base_grand_total - $applicationFee, 2) * 100,
                    "currency" => Cart::getCart()->base_currency_code,
                    "source" => $stripeToken,
                    "description" => "Purchased ".Cart::getCart()->items_count." items on ".config('app.name'),
                    "application_fee_amount" => $applicationFee * 100,
                    "statement_descriptor" => $this->statementDescriptor
                ], [
                    "stripe_account" => $sellerUserId
                ]);
            } else {
                $baseGrandTotal = $cart->base_grand_total;

                // admin's or owner's commission
                $ownerCommissionRate = env('STRIPE_ADMIN_COMMISSION') ?? 0.0;
                $ownerCommission = ($baseGrandTotal * $ownerCommissionRate) / 100;

                $applicationFee = round($ownerCommission, 2);

                $cart->update([
                    'base_grand_total' => $cart->grand_total + $applicationFee,
                    'grand_total' => $cart->grand_total + core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);

                $result = StripeCharge::create([
                    "amount" => round(Cart::getCart()->base_grand_total + $applicationFee, 2) * 100,
                    "currency" => Cart::getCart()->base_currency_code,
                    "source" => $stripeToken,
                    "description" => "Purchased ".Cart::getCart()->items_count." items on ".config('app.name'),
                    "application_fee_amount" => $applicationFee * 100,
                    "statement_descriptor" => $this->statementDescriptor
                ], [
                    "stripe_account" => $sellerUserId
                ]);
            }

        } catch(\Stripe\Error\Card $e) {
            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (\Stripe\Error\RateLimit $e) {
        // Too many requests made to the API too quickly
            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
        // Invalid parameters were supplied to Stripe's API

            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)

            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed

            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email

            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe

            if ($this->stripeFeeBearer == "customer") {
                $cart->update([
                    'base_grand_total' => $cart->grand_total - $applicationFee,
                    'grand_total' => $cart->grand_total - core()->convertPrice($applicationFee, $cart->cart_currency_code)
                ]);
            }

            $result = false;
        }

        return $result;
    }
}
