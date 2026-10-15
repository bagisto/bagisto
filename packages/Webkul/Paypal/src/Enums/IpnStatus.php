<?php

namespace Webkul\Paypal\Enums;

enum IpnStatus: string
{
    /**
     * PayPal did not confirm the notification came from it, so nothing was acted on.
     */
    case NOT_VERIFIED = 'not_verified';

    /**
     * The notification is one the store has nothing to do for, such as a recurring payment.
     */
    case IGNORED = 'ignored';

    /**
     * The money went to a PayPal account other than the one the store is configured to be paid into.
     */
    case WRONG_RECEIVER = 'wrong_receiver';

    /**
     * The notification named no cart, so there is nothing to settle it against.
     */
    case CART_NOT_IDENTIFIED = 'cart_not_identified';

    /**
     * The cart the notification names can no longer be ordered, so no order was placed.
     */
    case ORDER_NOT_PLACED = 'order_not_placed';

    /**
     * The amount or currency paid is not the one the order was raised for.
     */
    case AMOUNT_MISMATCH = 'amount_mismatch';

    /**
     * The order was moved to processing and the payment recorded against it.
     */
    case ORDER_SETTLED = 'order_settled';

    /**
     * The payment had already been recorded, by the buyer's return or an earlier notification.
     */
    case ALREADY_SETTLED = 'already_settled';

    /**
     * The notification could not be processed, and PayPal should send it again.
     */
    case ERROR = 'error';
}
