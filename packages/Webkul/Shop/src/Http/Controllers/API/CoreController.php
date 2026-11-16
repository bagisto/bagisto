<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\JsonResponse;

class CoreController extends APIController
{
    /**
     * Get every country.
     */
    public function getCountries(): JsonResponse
    {
        return response()->json([
            'data' => core()->countries()->map(fn ($country) => [
                'id' => $country->id,
                'code' => $country->code,
                'name' => $country->name,
            ]),
        ]);
    }

    /**
     * Get the states of every country.
     */
    public function getStates(): JsonResponse
    {
        return response()->json([
            'data' => core()->groupedStatesByCountries(),
        ]);
    }
}
