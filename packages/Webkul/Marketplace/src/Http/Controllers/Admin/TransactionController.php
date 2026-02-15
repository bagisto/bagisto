<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Admin\TransactionDataGrid;
use Webkul\Marketplace\Repositories\SellerTransactionRepository;

class TransactionController extends Controller
{
    public function __construct(protected SellerTransactionRepository $transactionRepository) {}

    /**
     * Display listing of transactions.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(TransactionDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.transactions');
    }

    /**
     * Create a manual transaction (payout).
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'seller_id'   => 'required|exists:marketplace_sellers,id',
            'type'        => 'required|in:credit,debit',
            'base_amount' => 'required|numeric|min:0.01',
            'comment'     => 'nullable|string|max:1000',
            'method'      => 'required|string',
        ]);

        $this->transactionRepository->create(request()->only([
            'seller_id',
            'type',
            'base_amount',
            'comment',
            'method',
        ]) + ['amount' => request('base_amount')]);

        session()->flash('success', trans('marketplace::app.admin.sellers.transaction-create-success'));

        return redirect()->route('admin.marketplace.transactions.index');
    }
}
