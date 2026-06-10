<?php

namespace Webkul\PagoMovil\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class PagoMovilController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(protected OrderRepository $orderRepository)
    {
    }

    /**
     * Redirect to the Pago Movil report page.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        $cart = Cart::getCart();
        
        // Obtenemos los datos configurados en el admin
        $bank = core()->getConfigData('sales.payment_methods.pagomovil.bank');
        $phone = core()->getConfigData('sales.payment_methods.pagomovil.phone');
        $id_number = core()->getConfigData('sales.payment_methods.pagomovil.id_number');

        return view('pagomovil::redirect', compact('cart', 'bank', 'phone', 'id_number'));
    }

    /**
     * Store the order after payment report.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        request()->validate([
            'reference'        => 'required',
            'bank_origin'      => 'required',
            'phone_origin'     => 'required',
            'id_number_origin' => 'required',
            'payment_date'     => 'required|date',
        ]);

        $cart = Cart::getCart();

        $data = (new OrderResource($cart))->jsonSerialize();

        // Agregamos la información adicional al pago
        $data['payment']['additional'] = [
            'reference'        => request('reference'),
            'bank_origin'      => request('bank_origin'),
            'phone_origin'     => request('phone_origin'),
            'id_number_origin' => request('id_number_origin'),
            'payment_date'     => request('payment_date'),
        ];

        $order = $this->orderRepository->create($data);

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }
}
