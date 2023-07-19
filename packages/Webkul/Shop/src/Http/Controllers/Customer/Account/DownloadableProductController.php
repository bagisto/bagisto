<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\Support\Facades\Storage;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

class DownloadableProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository  $downloadableLinkPurchasedRepository
     * @return void
     */
    public function __construct(protected DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $downloadableLinkPurchased = $this->downloadableLinkPurchasedRepository->findWhere([
            'customer_id' => auth()->guard('customer')->id(),
        ]);

        return view('shop::customers.account.downloadable_products.index')->with('downloadableLinkPurchased', $downloadableLinkPurchased);
    }

    /**
     * Download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $downloadableLinkPurchased = $this->downloadableLinkPurchasedRepository->findOneByField([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if ($downloadableLinkPurchased->status == 'pending') {
            abort(403);
        }

        $totalInvoiceQty = 0;
        if (isset($downloadableLinkPurchased->order->invoices)) {
            foreach ($downloadableLinkPurchased->order->invoices as $invoice) {
                $totalInvoiceQty = $totalInvoiceQty + $invoice->total_qty;
            }
        }

        $orderedQty = $downloadableLinkPurchased->order->total_qty_ordered;
        $totalInvoiceQty = $totalInvoiceQty * ($downloadableLinkPurchased->download_bought / $orderedQty);

        if (
            $downloadableLinkPurchased->download_used == $totalInvoiceQty
            || $downloadableLinkPurchased->download_used > $totalInvoiceQty
        ) {
            session()->flash('warning', trans('shop::app.customers.account.downloadable_products.payment-error'));

            return redirect()->route('shop.customer.downloadable_products.index');
        }

        if (
            $downloadableLinkPurchased->download_bought
            && ($downloadableLinkPurchased->download_bought - ($downloadableLinkPurchased->download_used + $downloadableLinkPurchased->download_canceled)) <= 0
        ) {

            session()->flash('warning', trans('shop::app.customers.account.downloadable-products.download-error'));

            return redirect()->route('shop.customer.downloadable_products.index');
        }

        $remainingDownloads = $downloadableLinkPurchased->download_bought - ($downloadableLinkPurchased->download_used + $downloadableLinkPurchased->download_canceled + 1);

        if ($downloadableLinkPurchased->download_bought) {
            $this->downloadableLinkPurchasedRepository->update([
                'download_used' => $downloadableLinkPurchased->download_used + 1,
                'status'        => $remainingDownloads <= 0 ? 'expired' : $downloadableLinkPurchased->status,
            ], $downloadableLinkPurchased->id);
        }

        if ($downloadableLinkPurchased->type == 'file') {
            $privateDisk = Storage::disk('private');

            return $privateDisk->exists($downloadableLinkPurchased->file)
                ? $privateDisk->download($downloadableLinkPurchased->file)
                : abort(404);
        } else {
            $fileName = $name = substr($downloadableLinkPurchased->url, strrpos($downloadableLinkPurchased->url, '/') + 1);

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($downloadableLinkPurchased->url, $tempImage);

            return response()->download($tempImage, $fileName);
        }
    }
}