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
     * Generates a formatted price string using the provided price, currencyCode and currenct localeCode.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormattedPrice()
    {
        $price = request()->input('price');
        $currencyCode = request()->input('currencyCode');

        return response()->json([
            'data' => core()->formatPrice($price, $currencyCode),
        ]);
    }
}
