<?php

namespace Webkul\Customer;

use Illuminate\Support\Collection;

class AddressSettings
{
    /**
     * Whether an address must carry a country on the given channel, the requested one by default.
     */
    public function isCountryRequired(?string $channelCode = null): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.country', $channelCode);
    }

    /**
     * Whether an address must carry a state on the given channel, the requested one by default.
     */
    public function isStateRequired(?string $channelCode = null): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.state', $channelCode);
    }

    /**
     * Whether an address must carry a postcode on the given channel, the requested one by default.
     */
    public function isPostCodeRequired(?string $channelCode = null): bool
    {
        return (bool) core()->getConfigData('customer.address.requirements.postcode', $channelCode);
    }

    /**
     * Retrieve how many street lines an address form shows on the given channel, at least one.
     */
    public function streetLines(?string $channelCode = null): int
    {
        return max(1, (int) core()->getConfigData('customer.address.information.street_lines', $channelCode));
    }

    /**
     * Retrieve the country codes the given channel is limited to, empty when every country is offered.
     */
    public function allowedCountryCodes(?string $channelCode = null): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', (string) core()->getConfigData('customer.address.countries.allowed', $channelCode))),
            fn (string $code) => $code !== ''
        ));
    }

    /**
     * Retrieve the countries a customer of the given channel can pick.
     */
    public function allowedCountries(?string $channelCode = null): Collection
    {
        $countries = core()->countries();

        $allowed = $this->allowedCountryCodes($channelCode);

        return $allowed
            ? $countries->whereIn('code', $allowed)->values()
            : $countries;
    }

    /**
     * Retrieve the states grouped by the countries a customer of the given channel can pick.
     */
    public function allowedGroupedStates(?string $channelCode = null): array
    {
        $allowed = $this->allowedCountryCodes($channelCode);

        $states = core()->groupedStatesByCountries();

        return $allowed
            ? array_intersect_key($states, array_flip($allowed))
            : $states;
    }
}
