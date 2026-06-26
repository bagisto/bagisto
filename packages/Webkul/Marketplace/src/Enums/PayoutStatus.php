<?php

namespace Webkul\Marketplace\Enums;

enum PayoutStatus: string
{
    case Requested  = 'requested';
    case Processing = 'processing';
    case Paid       = 'paid';
    case Rejected   = 'rejected';
}
