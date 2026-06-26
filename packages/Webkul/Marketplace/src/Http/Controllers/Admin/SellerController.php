<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerController extends Controller
{
    public function __construct(protected SellerRepository $sellerRepository) {}

    public function index(): View
    {
        $sellers = $this->sellerRepository->paginate(25);

        return view('marketplace::admin.sellers.index', compact('sellers'));
    }

    public function view(int $id): View
    {
        $seller = $this->sellerRepository->findOrFail($id);

        return view('marketplace::admin.sellers.view', compact('seller'));
    }

    public function approve(int $id): RedirectResponse
    {
        $this->sellerRepository->approve($id);

        session()->flash('success', trans('marketplace::app.admin.sellers.approve-success'));

        return redirect()->route('admin.marketplace.sellers.view', $id);
    }

    public function suspend(int $id): RedirectResponse
    {
        $this->sellerRepository->suspend($id);

        session()->flash('success', trans('marketplace::app.admin.sellers.suspend-success'));

        return redirect()->route('admin.marketplace.sellers.view', $id);
    }
}
