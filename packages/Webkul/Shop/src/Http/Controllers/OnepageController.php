<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Checkout\Repositories\CartRepository;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\BookingProduct\Models\BookingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Webkul\BookingProduct\Models\BookingProductDefaultSlot;
use Exception;
use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Models\CartItem;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;

class OnepageController extends Controller
{

    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index(CartRepository $cartRepository){

    $user = auth()->guard('customer')->user();

    if (! $user && ! core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')) {
        return redirect()->route('shop.customer.session.index');
    }

    if ($user?->is_suspended) {
        session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));
        return redirect()->route('shop.checkout.cart.index');
    }

    $carts = $user ? $cartRepository->findWhere(['customer_id' => $user->id]) : collect();

    $cartItems = collect();

    foreach ($carts as $cart) {
        $cartItems = $cartItems->merge(
            $cart->items()->with(['product','professional'])->get()
        );
    }

    $subtotal = $cartItems->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));
    $discount = 0;
    $total = $subtotal - $discount;
    $tax = $subtotal * 0.05;

    $availability = [];

    foreach ($cartItems as $item) {

        $productId = $item->product->id;

        $booking = BookingProduct::where('product_id',$productId)->first();

        if (!$booking) {
            continue;
        }

        // get all slots once
        $slots = BookingProductDefaultSlot::
                    where('booking_product_id',$booking->id)
                    ->pluck('slots')
                    ->toArray();

        $start = \Carbon\Carbon::parse($booking->available_from);
        $end   = \Carbon\Carbon::parse($booking->available_to);

        while ($start->lte($end)) {

            $date = $start->format('Y-m-d');

            $availability[$productId][$date] = $slots;

            $start->addDay();
        }
    }

    return view('shop::checkout.onepage.index', compact(
        'cartItems',
        'subtotal',
        'discount',
        'total',
        'tax',
        'availability'
    ));
}

    public function success(OrderRepository $orderRepository)
    {
        if (! $order = $orderRepository->find(session('order_id'))) {
            return redirect()->route('shop.checkout.cart.index');
        }

        if (
            core()->getConfigData('general.magic_ai.settings.enabled')
            && core()->getConfigData('general.magic_ai.checkout_message.enabled')
            && ! empty(core()->getConfigData('general.magic_ai.checkout_message.prompt'))
        ) {

            try {
                $model = core()->getConfigData('general.magic_ai.checkout_message.model');

                $response = MagicAI::setModel($model)
                    ->setTemperature(0)
                    ->setPrompt($this->getCheckoutPrompt($order))
                    ->ask();

                $order->checkout_message = $response;
            } catch (\Exception $e) {
            }
        }

        return view('shop::checkout.success', compact('order'));
    }

    /**
     * Order success page.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return string
     */
    public function getCheckoutPrompt($order)
    {
        $prompt = core()->getConfigData('general.magic_ai.checkout_message.prompt');

        $products = '';

        foreach ($order->items as $item) {
            $products .= "Name: $item->name\n";
            $products .= "Qty: $item->qty_ordered\n";
            $products .= 'Price: '.core()->formatPrice($item->total)."\n\n";
        }

        $prompt .= "\n\nProduct Details:\n $products";

        $prompt .= "Customer Details:\n $order->customer_full_name \n\n";

        $prompt .= "Current Locale:\n ".core()->getCurrentLocale()->name."\n\n";

        $prompt .= "Store Name:\n".core()->getCurrentChannel()->name;

        return $prompt;
    }


public function checkoutService(AddressRequest $req)
{
    $cart = Cart::getCart();

    if (!$cart) {
        return redirect()->back()->with('error', 'Cart not found');
    }

    DB::beginTransaction();

    try {

        $user = Auth::user();

        // Create Order
        $orderId = DB::table('orders')->insertGetId([
            'increment_id'            => 'ORD' . time(),
            'status'                  => 'pending',
            'payment_method'          => $paymentMethod,
            'channel_name'            => core()->getCurrentChannel()->name,
            'is_guest'                => 0,
            'customer_email'          => $user->email,
            'customer_first_name'     => $user->first_name,
            'customer_last_name'      => $user->last_name,

            'total_item_count'        => $cart->items_qty,
            'total_qty_ordered'       => $cart->items_qty,

            'base_currency_code'      => core()->getBaseCurrencyCode(),
            'channel_currency_code'   => core()->getCurrentCurrencyCode(),
            'order_currency_code'     => core()->getCurrentCurrencyCode(),

            'grand_total'             => $cart->grand_total,
            'base_grand_total'        => $cart->grand_total,

            'sub_total'               => $cart->sub_total,
            'base_sub_total'          => $cart->sub_total,

            'customer_id'             => $user->id,
            'customer_type'           => 'customer',

            'channel_id'              => core()->getCurrentChannel()->id,
            'channel_type'            => 'Webkul\Channel\Models\Channel',

            'cart_id'                 => $cart->id,

            'created_at'              => now(),
            'updated_at'              => now(),
        ]);


        // Fetch Cart Items
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        foreach ($cartItems as $item) {

            DB::table('order_items')->insert([
                'order_id'        => $orderId,
                'method' => $paymentMethod,
                'product_id'      => $item->product_id,
                'type'            => $item->type,
                'name'            => $item->product->name ?? 'Service',
                'qty_ordered'     => $item->quantity,

                'price'           => $item->price,
                'base_price'      => $item->price,

                'total'           => $item->total,
                'base_total'      => $item->total,

                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        DB::commit();

        return redirect()->route('shop.checkout.page', $orderId);

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect()->back()->with('error', $e->getMessage());
    }
}

   
}
