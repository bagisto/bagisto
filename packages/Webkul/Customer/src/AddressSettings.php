<?php

namespace Webkul\Customer;

use Illuminate\Support\Collection;

class AddressSettings
{
    /**
     * Whether an address must carry a country.
     */
    public function isCountryRequired(): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.country');
    }

    /**
     * Whether an address must carry a state.
     */
    public function isStateRequired(): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.state');
    }

    /**
     * Whether an address must carry a postcode.
     */
    public function isPostCodeRequired(): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.postcode');
    }

    /**
     * Retrieve the country codes the storefront is limited to, empty when every country is offered.
     */
    public function allowedCountryCodes(): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', (string) core()->getConfigData('customer.address.countries.allowed'))),
            fn (string $code) => $code !== ''
        ));
    }

    /**
     * Retrieve the countries a customer can pick on the storefront.
     */
    public function allowedCountries(): Collection
    {
        $countries = core()->countries();

        $allowed = $this->allowedCountryCodes();

        return $allowed
            ? $countries->whereIn('code', $allowed)->values()
            : $countries;
    }

    /**
     * Retrieve the states grouped by the countries a customer can pick on the storefront.
     */
    public function allowedGroupedStates(): array
    {
        $allowed = $this->allowedCountryCodes();

        $states = core()->groupedStatesByCountries();

        return $allowed
            ? array_intersect_key($states, array_flip($allowed))
            : $states;
    }
}
