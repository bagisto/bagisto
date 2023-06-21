<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Shop\Http\Resources\AddressResource;

class AddressController extends APIController
{
    /**
     * Customer addresses
     */
    public function index(): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        return AddressResource::collection($customer->addresses);
    }
}
