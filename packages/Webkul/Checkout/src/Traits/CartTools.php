<?php

namespace Webkul\Checkout\Traits;

/**
 * Cart tools. In this trait, you will get all sorted collections of
 * methods which can be used to manipulate cart and its items.
 *
 * Note: This trait will only work with the Cart facade. Unless and until,
 * you have all the required repositories in the parent class.
 */
trait CartTools
{
    /**
     * Save cart for guest.
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function putCart($cart)
    {
        if (! $this->getCurrentCustomer()->check()) {
            session()->put('cart', $cart);
        }
    }

    /**
     * This method handles when guest has some of cart products and then logs in.
     *
     * @return void
     */
    public function mergeCart(): void
    {
        if (session()->has('cart')) {
            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => $this->getCurrentCustomer()->user()->id,
                'is_active'   => 1,
            ]);

            $guestCart = session()->get('cart');

            /**
             * When the logged in customer is not having any of the cart instance previously and are active.
             */
            if (! $cart) {
                $this->cartRepository->update([
                    'customer_id'         => $this->getCurrentCustomer()->user()->id,
                    'is_guest'            => 0,
                    'customer_first_name' => $this->getCurrentCustomer()->user()->first_name,
                    'customer_last_name'  => $this->getCurrentCustomer()->user()->last_name,
                    'customer_email'      => $this->getCurrentCustomer()->user()->email,
                ], $guestCart->id);

                session()->forget('cart');

                return;
            }

            foreach ($guestCart->items as $guestCartItem) {
                $this->addProduct($guestCartItem->product_id, $guestCartItem->additional);
            }

            $this->collectTotals();

            $this->cartRepository->delete($guestCart->id);

            session()->forget('cart');
        }
    }

    /**
     * This method will merge deactivated cart, when a user suddenly navigates away
     * from the checkout after click the buy now button.
     *
     * @return void
     */
    public function mergeDeactivatedCart(): void
    {
        if (session()->has('deactivated_cart_id')) {
            $deactivatedCartId = session()->get('deactivated_cart_id');

            if ($this->getCart()) {
                $deactivatedCart = $this->cartRepository->find($deactivatedCartId);

                foreach ($deactivatedCart->items as $deactivatedCartItem) {
                    $this->addProduct($deactivatedCartItem->product_id, $deactivatedCartItem->additional);
                }

                $this->collectTotals();
            } else {
                $this->cartRepository->update(['is_active' => true], $deactivatedCartId);
            }

            session()->forget('deactivated_cart_id');
        }
    }

    /**
     * This method will reactivate the cart which is deactivated at the the time of buy now functionality.
     *
     * @return void
     */
    public function activateCartIfSessionHasDeactivatedCartId(): void
    {
        if (session()->has('deactivated_cart_id')) {
            $deactivatedCartId = session()->get('deactivated_cart_id');

            $this->cartRepository->update(['is_active' => true], $deactivatedCartId);

            session()->forget('deactivated_cart_id');
        }
    }

    /**
     * Deactivates current cart.
     *
     * @return void
     */
    public function deActivateCart(): void
    {
        if ($cart = $this->getCart()) {
            $this->cartRepository->update(['is_active' => false], $cart->id);

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }
}
