<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class AuthController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected CustomerRepository $customerRepository,
    ) {}

    public function create(): View
    {
        return view('marketplace::seller.auth.register');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'shop_name' => 'required|string|max:191|unique:marketplace_sellers,shop_name',
            'shop_url'  => 'required|string|max:191|unique:marketplace_sellers,shop_url|alpha_dash',
            'phone'     => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return redirect()->route('customer.session.index')
                ->with('error', trans('marketplace::app.seller.auth.login-required'));
        }

        if ($this->sellerRepository->findByCustomer($customer->id)) {
            return redirect()->route('marketplace.dashboard')
                ->with('info', trans('marketplace::app.seller.auth.already-registered'));
        }

        $this->sellerRepository->create([
            'customer_id' => $customer->id,
            'shop_name'   => request('shop_name'),
            'shop_url'    => request('shop_url'),
            'phone'       => request('phone'),
            'description' => request('description'),
        ]);

        return redirect()->route('marketplace.dashboard')
            ->with('success', trans('marketplace::app.seller.auth.register-success'));
    }
}
