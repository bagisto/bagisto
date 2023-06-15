<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Shop\Http\Resources\AddressResource;

class AddressController extends APIController
{
    /**
     * Customer addresses
     */
    public function index(): JsonResource
    {
        $customer = Auth::guard('customer')->user();

        return AddressResource::collection($customer->addresses);
    }
}
