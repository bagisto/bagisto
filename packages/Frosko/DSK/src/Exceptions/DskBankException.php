<?php

declare(strict_types=1);

namespace Frosko\DSK\Exceptions;

class DskBankException extends \Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct("DSK Bank API Error: {$message}", $code);
    }
}
