<?php

namespace Helper;

use Codeception\Module;
use Faker\Factory;
use Faker\Generator;

class DataMocker extends Module
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
