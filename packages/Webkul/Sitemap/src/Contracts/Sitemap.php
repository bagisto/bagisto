<?php

namespace Webkul\Sitemap\Contracts;

interface Sitemap
{
    /**
     * Define the default frequency.
     *
     * @var string
     */
    public const DEFAULT_FREQUENCY = 'daily';

    /**
     * Define the default priority.
     *
     * @var float
     */
    public const DEFAULT_PRIORITY = 0.8;

    /**
     * Define the default max urls.
     *
     * @var int
     */
    public const DEFAULT_MAX_URLS = 50000;
}
