<?php

namespace Webkul\SAASPreOrder\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\SAASPreOrder\Repositories\PreOrderItemRepository;
use Cart;

/**
 * PreOrder page controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PreOrderController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var array
    */
    protected $productRepository;

    /**
     * OrderItemRepository object
     *
     * @var array
    */
    protected $orderItemRepository;

    /**
     * PreOrderItemRepository object
     *
     * @var array
    */
    protected $preOrderItemRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository       $productRepository
     * @param  Webkul\Sales\Repositories\OrderItemRepository       $orderItemRepository
     * @param  Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        OrderItemRepository $orderItemRepository,
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->productRepository = $productRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function complete()
    {
        if (request()->route('token'))
            $preOrderItem = $this->preOrderItemRepository->findOneByField('token', request()->route('token'));
        else
            $preOrderItem = $this->preOrderItemRepository->find(request()->input('id'));

        if (! $preOrderItem)
            return abort(404);

        $orderItem = $this->orderItemRepository->findOrFail($preOrderItem->order_item_id);

        if (! $this->preOrderItemRepository->canBeComplete($orderItem)) {
            session()->flash('error', trans('preorder::app.shop.preorders.complete-preorder-error'));

            if (request()->route('token'))
                return redirct()->route('shop.home.index');
            else
                return back();
        }

        $data = [];

        if ($orderItem->type == 'configurable') {
            $data = [
                'pre_order_payment' => true,
                'order_item_id' => $preOrderItem->order_item_id,
                'product' => $orderItem->product_id,
                'quantity' => $orderItem->qty_ordered,
                'is_configurable' => true,
                'selected_configurable_option' => $orderItem->child->product_id
            ];

            foreach ($this->productRepository->getSuperAttributes($orderItem->product) as $attribute) {
                $data['super_attribute'][$attribute['id']] = $orderItem->child->product->{$attribute['code']};
            }
        } else {
            $data = [
                'pre_order_payment' => true,
                'order_item_id' => $preOrderItem->order_item_id,
                'product' => $orderItem->product_id,
                'quantity' => $orderItem->qty_ordered,
                'is_configurable' => false,
            ];
        }

        request()->request->add($data);

        $eventResult = Event::fire('checkout.cart.add.before', $data['product']);

        $flag = true;
        foreach ($eventResult as $res) {
            if ($res != null) {
                $flag = false;
            }
        }

        if ($flag) {
            $result = Cart::add($data['product'], $data);

            Event::fire('checkout.cart.add.after', $result);
        }

        return redirect()->route('shop.checkout.onepage.index');
    }
}