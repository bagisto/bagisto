<?php

namespace Helper;

use Faker\Factory;
use Faker\Generator;

class DataMocker extends \Codeception\Module
{
    /**
     * Faker instance.
     *
     * @var \Faker\Generator
     */
    private static $faker;

    /**
     * Get an instance of the faker.
     *
     * @return \Faker\Generator
     */
    public function fake(): Generator
    {
        if (self::$faker === null) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}
