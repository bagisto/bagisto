<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Webkul\Core\Tests\Concerns\ConfiguresSettings;

abstract class TestCase extends BaseTestCase
{
    use ConfiguresSettings, DatabaseTransactions;
}
