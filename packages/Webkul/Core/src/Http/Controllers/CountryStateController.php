<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\CountryRepository as Country;
use Webkul\Core\Repositories\CountryStateRepository as State;

/**
 * Country controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CountryStateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CountryRepository object
     *
     * @var array
     */
    protected $country;

    /**
     * StateRepository object
     *
     * @var array
     */
    protected $state;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CountryRepository  $country
     * @return void
     */
    public function __construct(Country $country, State $state)
    {
        $this->country = $country;

        $this->state = $state;

        $this->_config = request('_config');
    }

    /**
     * Function to retrieve states with respect to countries with codes and names for both of the countries and states.
     *
     * @return array
     */
    public function getCountries() {
        $countries = $this->country->all();
        $states = $this->state->all();

        $nestedArray = [];

        foreach($countries as $keyCountry => $country) {
            foreach($states as $keyState => $state) {
                if($country->code == $state->country_code) {
                    $nestedArray[$country->name][$state->code] = $state->default_name;
                }
            }
        }

        return view($this->_config['view'])->with('statesCountries', $nestedArray);
    }

    public function getStates($country) {
        $countries = $this->country->all();
        $states = $this->state->all();

        $nestedArray = [];

        foreach($countries as $keyCountry => $country) {
            foreach($states as $keyState => $state) {
                if($country->code == $state->country_code) {
                    $nestedArray[$country->name][$state->code] = $state->default_name;
                }
            }
        }

        return view($this->_config['view'])->with('statesCountries', $nestedArray);
    }
}