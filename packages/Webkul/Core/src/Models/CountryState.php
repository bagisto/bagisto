<?php

namespace Webkul\Core\Models;

use Webkul\Core\Contracts\CountryState as CountryStateContract;
use Webkul\Core\Eloquent\TranslatableModel;

class CountryState extends TranslatableModel implements CountryStateContract
{
    public $timestamps = false;

    public $translatedAttributes = ['default_name'];

    protected $with = ['translations'];

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['default_name'] = $this->default_name;

        return $array;
    }
}
