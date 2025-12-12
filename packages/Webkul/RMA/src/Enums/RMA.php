<?php

namespace Webkul\RMA\Enums;

enum RMA: string
{
    case ACCEPT = 'accept';

    case CANCELED = 'canceled';

    case CLOSED = 'closed';

    case ITEMCANCELED = 'item_canceled';

    case DECLINED = 'declined';

    case PENDING = 'pending';

    case RECEIVEDPACKAGE = 'received_package';

    case SOLVED = 'solved';

    case INACTIVE = '0';

    case ACTIVE = '1';
}
