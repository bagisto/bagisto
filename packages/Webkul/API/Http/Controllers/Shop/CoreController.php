<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Http\Request;

/**
 * Core controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CoreController extends Controller
{
    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getConfig()
    {
        $configValues = [];

        foreach (explode(',', request()->input('_config')) as $config) {
            $configValues[$config] = core()->getConfigData($config);
        }
        
        return response()->json([
            'data' => $configValues,
        ]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountryStateGroup()
    {
        return response()->json([
            'data' => core()->groupedStatesByCountries(),
        ]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function switchCurrency()
    {
        return response()->json([]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function switchLocale()
    {
        return response()->json([]);
    }
}
