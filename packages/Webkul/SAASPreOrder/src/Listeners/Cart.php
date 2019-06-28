<?php

namespace Webkul\SAASPreOrder\Listeners;

use Cart as CartFacade;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Helpers\Price as PriceHelper;
use Webkul\SAASPreOrder\Repositories\PreOrderItemRepository;

/**
 * Cart event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart
{
    /**
     * PriceHelper object
     *
     * @var Object
    */
    protected $priceHelper;

    /**
     * ProductRepository object
     *
     * @var Product
    */
    protected $productRepository;

    /**
     * PreOrderItemRepository object
     *
     * @var Product
    */
    protected $preOrderItemRepository;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Product\Helpers\Price                        $priceHelper
     * @param  Webkul\Product\Repositories\ProductRepository       $productRepository
     * @param  Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        PriceHelper $priceHelper,
        ProductRepository $productRepository,
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->priceHelper = $priceHelper;

        $this->productRepository = $productRepository;

        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * Before product added to the cart
     *
     * @param mixed $cartItem
     */
    public function cartItemAddBefore($productId)
    {
        $data = request()->all();

        if (! isset($data['pre_order_payment'])) {
            if ($this->haveCompletePreorderProduct($productId))
                throw new \Exception('Product can not be added with preorder payment.');

            $product = $this->productRepository->find($productId);

            if ($product->type == 'configurable') {
                if (isset($data['selected_configurable_option'])) {
                    $product = $this->productRepository->find($data['selected_configurable_option']);
                } else {
                    return;
                }
            }

            if ($product->totalQuantity() > 0 || ! $product->allow_preorder)
                return;

            if (! isset($data['quantity']))
                $data['quantity'] = 1;

            if ($cart = CartFacade::getCart()) {
                $cartItem = $cart->items()->where('product_id', $productId)->first();

                if ($cartItem) {
                    $quantity = $cartItem->quantity + $data['quantity'];
                } else {
                    $quantity = $data['quantity'];
                }
            } else {
                $quantity = $data['quantity'];
            }

            if ($product->preorder_qty && $product->preorder_qty < $quantity)
                throw new \Exception('Requested quantity not available for preorder.');
        } else {
            if ($cart = CartFacade::getCart()) {
                $cartItem = $cart->items()->where('product_id', $productId)->first();

                if ($cartItem) {
                    throw new \Exception('Invalid quantity for complete preorder payment.');
                } else {
                    throw new \Exception('Preorder payment can not be added with other product.');
                }
            }
        }
    }

    /**
     * @param integer $productId
     */
    public function haveCompletePreorderProduct($productId)
    {
        if (! $cart = CartFacade::getCart())
            return false;

        foreach ($cart->items()->get() as $item) {
            if (isset($item->additional['pre_order_payment']))
                return true;
        }

        return false;
    }

    /**
     * Before product update to the cart
     *
     * @param CartItem $item
     */
    public function cartItemUpdateBefore($item)
    {
        if (isset($item->additional['pre_order_payment']))
            throw new \Exception('Preoder payment qunatity can not be updated.');

        $quantities = request()->get('qty');

        $product = $item->type == 'configurable' ? $item->child->product_flat : $item->product_flat;

        if ($product->totalQuantity() > 0 || ! $product->allow_preorder)
            return;

        if ($product->preorder_qty && $product->preorder_qty < $quantities[$item->id])
            throw new \Exception('Requested quantity not available for preorder.');
    }

    /**
     * After product added to the cart
     *
     * @param mixed $cartItem
     */
    public function cartItemAddAfter($cartItem)
    {
        if (! request()->input('pre_order_payment')) {
            $product = $this->productRepository->find($cartItem->product_id);

            if ($product->totalQuantity() > 0 || ! $product->allow_preorder)
                return;

            if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial') {
                if (is_null(core()->getConfigData('preorder.settings.general.percent'))) {
                    $preOrderPercentage =  0;
                } else {
                    $preOrderPercentage = core()->getConfigData('preorder.settings.general.percent');
                }
            } else {
                $preOrderPercentage = 100;
            }

            $productPrice = $cartItem->type == 'configurable'
                    ? ($this->priceHelper->getMinimalPrice($cartItem->child->product_flat) * $preOrderPercentage) / 100
                    : ($this->priceHelper->getMinimalPrice($cartItem->product_flat) * $preOrderPercentage) / 100;

            $cartItem->price = core()->convertPrice($productPrice);
            $cartItem->base_price = $productPrice;
            $cartItem->custom_price = $productPrice;

            $cartItem->save();
        } else {
            $preOrderItem = $this->preOrderItemRepository->findOneByField('order_item_id', request()->input('order_item_id'));

            $productPrice = $preOrderItem->base_remaining_amount / $preOrderItem->order_item->qty_ordered;

            $cartItem->price = core()->convertPrice($productPrice);
            $cartItem->base_price = $productPrice;
            $cartItem->custom_price = $productPrice;

            $cartItem->save();
        }
    }
}