<?php

namespace Tests\API\V1;

class BaseCest
{
    protected const API_VERSION = 'v1';

    protected function getVersionRoute($url)
    {
        return self::API_VERSION . '/' . $url;
    }
}
