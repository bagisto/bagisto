<?php
declare(strict_types=1);

namespace Frosko\DSK\Enums;

readonly class ApiDefaults
{
    public const SESSION_TIMEOUT_SECS = 1200;
    public const API_TIMEOUT_SECS = 30;
    public const API_RETRY_TIMES = 2;
    public const API_RETRY_SLEEP_MS = 100;
    public const USER_AGENT = 'Frosko-DSK-Client/1.0';
}
