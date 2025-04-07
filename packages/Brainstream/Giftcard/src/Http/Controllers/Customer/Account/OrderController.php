<?php

namespace Brainstream\Giftcard\Http\Controllers\Customer\Account;


use Webkul\Shop\Http\Controllers\Customer\Account\OrderController as BaseOrderController;
use Brainstream\Giftcard\Models\GiftCardBalance;
use Brainstream\Giftcard\Models\GiftCard;

class OrderController extends BaseOrderController
{
    public function cancel($id)
    {
        $customer = auth()->guard('customer')->user();

        $order = $customer->orders()->find($id);

        if (! $order) {
            abort(404);
        }

        $result = $this->orderRepository->cancel($order);

        if ($result) {
            if ($order->giftcard_number) {
                $giftCardBalance = GiftCardBalance::where('giftcard_number', $order->giftcard_number)->first();
                $giftCard = GiftCard::where('giftcard_number', $order->giftcard_number)->first();

                if ($giftCardBalance && $giftCard) {
                    $giftCardBalance->used_giftcard_amount -= $order->giftcard_amount;
                    $giftCardBalance->remaining_giftcard_amount += $order->giftcard_amount;

                    if ($giftCardBalance->remaining_giftcard_amount > $giftCardBalance->giftcard_amount) {
                        $giftCardBalance->remaining_giftcard_amount = $giftCardBalance->giftcard_amount;
                    }

                    $giftCardBalance->save();

                    $giftCard->giftcard_amount += $order->giftcard_amount;
                    $giftCard->save();
                }
            }

            session()->flash('success', trans('shop::app.customers.account.orders.view.cancel-success', ['name' => trans('admin::app.customers.account.orders.order')]));
        } else {
            session()->flash('error', trans('shop::app.customers.account.orders.view.cancel-error', ['name' => trans('admin::app.customers.account.orders.order')]));
        }

        return redirect()->back();
    }
}
