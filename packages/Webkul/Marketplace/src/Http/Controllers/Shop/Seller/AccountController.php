<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;

class AccountController extends Controller
{
    public function __construct(protected SellerRepository $sellerRepository) {}

    /**
     * Show seller registration form.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if ($seller) {
            return redirect()->route('marketplace.seller.dashboard');
        }

        return view('marketplace::shop.seller.account.register');
    }

    /**
     * Register a new seller.
     */
    public function register(): RedirectResponse
    {
        request()->validate([
            'shop_title'  => 'required|string|max:255',
            'url'         => 'required|string|max:255|unique:marketplace_sellers,url|alpha_dash',
            'description' => 'nullable|string|max:2000',
            'phone'       => 'nullable|string|max:20',
            'address1'    => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:100',
            'state'       => 'nullable|string|max:100',
            'country'     => 'nullable|string|max:5',
            'postcode'    => 'nullable|string|max:20',
        ]);

        $customer = auth()->guard('customer')->user();

        $approvalRequired = (bool) core()->getConfigData('marketplace.settings.general.seller_approval_required');

        $this->sellerRepository->create([
            'customer_id' => $customer->id,
            'shop_title'  => request('shop_title'),
            'url'         => Str::slug(request('url')),
            'description' => request('description'),
            'is_approved' => ! $approvalRequired,
            'status'      => true,
            'phone'       => request('phone'),
            'address1'    => request('address1'),
            'city'        => request('city'),
            'state'       => request('state'),
            'country'     => request('country'),
            'postcode'    => request('postcode'),
        ]);

        if ($approvalRequired) {
            session()->flash('success', trans('marketplace::app.shop.seller.registration-pending'));
        } else {
            session()->flash('success', trans('marketplace::app.shop.seller.registration-success'));
        }

        return redirect()->route('marketplace.seller.dashboard');
    }

    /**
     * Show seller account edit form.
     */
    public function edit(): View
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.seller.register');
        }

        return view('marketplace::shop.seller.account.edit', compact('seller'));
    }

    /**
     * Update seller account.
     */
    public function update(): RedirectResponse
    {
        request()->validate([
            'shop_title'  => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'phone'       => 'nullable|string|max:20',
            'address1'    => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:100',
            'state'       => 'nullable|string|max:100',
            'country'     => 'nullable|string|max:5',
            'postcode'    => 'nullable|string|max:20',
        ]);

        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $this->sellerRepository->update(request()->only([
            'shop_title',
            'description',
            'phone',
            'address1',
            'address2',
            'city',
            'state',
            'country',
            'postcode',
        ]), $seller->id);

        session()->flash('success', trans('marketplace::app.shop.seller.account-updated'));

        return redirect()->back();
    }
}
