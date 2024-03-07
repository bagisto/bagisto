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
}
