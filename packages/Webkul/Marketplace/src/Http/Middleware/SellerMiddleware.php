<?php

namespace Webkul\Marketplace\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerMiddleware
{
    public function __construct(protected SellerRepository $sellerRepository) {}

    /**
     * Handle an incoming request. Ensures the authenticated customer is an approved seller.
     */
    public function handle(Request $request, Closure $next)
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return redirect()->route('shop.customer.session.index');
        }

        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller || ! $seller->is_approved) {
            session()->flash('warning', trans('marketplace::app.shop.seller.not-approved'));

            return redirect()->route('marketplace.seller.register');
        }

        $request->merge(['seller' => $seller]);

        return $next($request);
    }
}
