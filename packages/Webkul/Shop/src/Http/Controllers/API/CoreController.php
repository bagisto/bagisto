<?php

namespace Webkul\Shop\Http\Controllers\API;

class CoreController extends APIController
{
    /**
     * Get countries.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries()
    {
        return response()->json([
            'data' => core()->countries()->map(fn ($country) => [
                'id'   => $country->id,
                'code' => $country->code,
                'name' => $country->name,
            ]),
        ]);
    }

    /**
     * Get states.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStates()
    {
        return response()->json([
            'data' => core()->groupedStatesByCountries(),
        ]);
    }

    /**
     * Generates a formatted price string using the provided price and currency code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormattedPrice()
    {
        $prices = request()->input('prices');

        $currencyCode = request()->input('currencyCode');

        if (is_array($prices)) {
            $formattedPrices = collect($prices)
                ->map(fn ($price) => core()->formatPrice($price, $currencyCode))
                ->toArray();

            return response()->json([
                'data' => $formattedPrices,
            ]);
        }

        return response()->json([
            'data' => core()->formatPrice($prices, $currencyCode),
        ]);
    }
}
