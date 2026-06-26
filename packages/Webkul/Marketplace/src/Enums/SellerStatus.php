<?php

namespace Webkul\Marketplace\Enums;

enum SellerStatus: string
{
    case Pending   = 'pending';
    case Approved  = 'approved';
    case Rejected  = 'rejected';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Pendente',
            self::Approved  => 'Aprovado',
            self::Rejected  => 'Rejeitado',
            self::Suspended => 'Suspenso',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'warning',
            self::Approved  => 'success',
            self::Rejected  => 'danger',
            self::Suspended => 'secondary',
        };
    }
}
