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
     * Remove cart and destroy the session
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function removeCart($cart)
    {
        $this->cartRepository->delete($cart->id);

        if (session()->has('cart')) {
            session()->forget('cart');
        }

        $this->resetCart();
    }

    /**
     * Save cart for guest.
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function putCart($cart)
    {
        if (! auth()->guard()->check()) {
            $cartTemp = new \stdClass();
            $cartTemp->id = $cart->id;

            session()->put('cart', $cartTemp);
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
            $user = auth()->guard()->user();

            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => $user->id,
                'is_active'   => 1,
            ]);

            $this->setCart($cart);

            $guestCart = $this->cartRepository->find(session()->get('cart')->id);

            /**
             * When the logged in customer is not having any of the cart instance previously and are active.
             */
            if (! $cart) {
                $this->cartRepository->update([
                    'customer_id'         => $user->id,
                    'is_guest'            => 0,
                    'customer_first_name' => $user->first_name,
                    'customer_last_name'  => $user->last_name,
                    'customer_email'      => $user->email,
                ], $guestCart->id);

                session()->forget('cart');

                return;
            }

            foreach ($guestCart->items as $guestCartItem) {
                try {
                    $cart = $this->addProduct($guestCartItem->product_id, $guestCartItem->additional);
                } catch (\Exception $e) {
                    //Ignore exception
                }
            }

            $this->collectTotals();

            $this->removeCart($guestCart);
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
     * This method will deactivate the current cart if
     * buy now is active.
     *
     * @return void
     */
    public function deactivateCurrentCartIfBuyNowIsActive()
    {
        if (request()->get('is_buy_now')) {
            if ($deactivatedCart = $this->getCart()) {
                session()->put('deactivated_cart_id', $deactivatedCart->id);

                $this->deActivateCart();
            }
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

            $this->activateCart($deactivatedCartId);

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
            $cart = $this->cartRepository->update(['is_active' => false], $cart->id);

            $this->resetCart();

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }

    /**
     * Activate the cart by id.
     *
     * @param  int  $cartId
     * @return void
     */
    public function activateCart($cartId): void
    {
        $this->cartRepository->update(['is_active' => true], $cartId);

        $this->putCart($this->cartRepository->find($cartId));
    }

    /**
     * Move a wishlist item to cart.
     *
     * @param  \Webkul\Customer\Contracts\WishlistItem  $wishlistItem
     * @return bool
     */
    public function moveToCart($wishlistItem)
    {
        if (! $wishlistItem->product->getTypeInstance()->canBeMovedFromWishlistToCart($wishlistItem)) {
            return false;
        }

        if (! $wishlistItem->additional) {
            $wishlistItem->additional = [
                'product_id' => $wishlistItem->product_id,
                'quantity'   => request()->input('quantity'),
            ];
        }

        request()->merge($wishlistItem->additional);

        $result = $this->addProduct($wishlistItem->product_id, $wishlistItem->additional);

        if ($result) {
            $this->wishlistRepository->delete($wishlistItem->id);

            return true;
        }

        return false;
    }

    /**
     * Function to move a already added product to wishlist will run only on customer
     * authentication.
     *
     * @param  int  $itemId
     * @return bool
     */
    public function moveToWishlist($itemId)
    {
        $cart = $this->getCart();

        $cartItem = $cart->items()->find($itemId);

        if (! $cartItem) {
            return false;
        }

        $wishlistItems = $this->wishlistRepository->findWhere([
            'customer_id' => auth()->guard()->user()->id,
            'product_id'  => $cartItem->product_id,
        ]);

        $found = false;

        foreach ($wishlistItems as $wishlistItem) {
            $options = $wishlistItem->item_options;

            if (! $options) {
                $options = ['product_id' => $wishlistItem->product_id];
            }

            if ($cartItem->getTypeInstance()->compareOptions($cartItem->additional, $options)) {
                $found = true;
            }
        }

        if (! $found) {
            $this->wishlistRepository->create([
                'channel_id'  => $cart->channel_id,
                'customer_id' => auth()->guard()->user()->id,
                'product_id'  => $cartItem->product_id,
                'additional'  => $cartItem->additional,
            ]);
        }

        $result = $this->cartItemRepository->delete($itemId);

        if (! $cart->items->count()) {
            $this->cartRepository->delete($cart->id);
        } else {
            $this->collectTotals();
        }

        return true;
    }
}
