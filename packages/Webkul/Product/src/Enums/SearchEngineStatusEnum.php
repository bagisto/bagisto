<?php

namespace Webkul\Product\Enums;

enum SearchEngineStatusEnum: string
{
    /**
     * The engine answered and is usable.
     */
    case AVAILABLE = 'available';

    /**
     * Nothing answered.
     */
    case UNREACHABLE = 'unreachable';

    /**
     * Something answered but rejected the credentials.
     */
    case UNAUTHORIZED = 'unauthorized';

    /**
     * Something answered but is not a server this engine supports.
     */
    case INCOMPATIBLE = 'incompatible';

    /**
     * The connection itself has not been configured.
     */
    case MISCONFIGURED = 'misconfigured';

    /**
     * Whether this status means the engine may be used.
     */
    public function isUsable(): bool
    {
        return $this === self::AVAILABLE;
    }
}
