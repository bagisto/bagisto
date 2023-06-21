<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\Shop\Http\Resources\CartRuleResource;

class CartRuleController extends APIController
{
    /**
     * Create a new controller instance
     * 
     * @return void
     */
    public function __construct(protected CartRuleRepository $cartRuleRepository) {}

    /**
     * Customer addresses
     */
    public function index(): JsonResource
    {
        $cartRules = $this->cartRuleRepository->with('coupon_code')->findWhere(['status' => 1]);

        return CartRuleResource::collection($cartRules);
    }
}
