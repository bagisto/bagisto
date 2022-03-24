<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

class DownloadableProductController extends Controller
{
    /**
     * DownloadableLinkPurchasedRepository object
     *
     * @var \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository
     */
    protected $downloadableLinkPurchasedRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository  $downloadableLinkPurchasedRepository
     * @return void
     */
    public function __construct(
        DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository
    )
    {
        $this->middleware('customer');

        $this->downloadableLinkPurchasedRepository = $downloadableLinkPurchasedRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        return view($this->_config['view']);
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

        if ($downloadableLinkPurchased->download_used == $totalInvoiceQty || $downloadableLinkPurchased->download_used > $totalInvoiceQty) {
            session()->flash('warning', trans('shop::app.customer.account.downloadable_products.payment-error'));

            return redirect()->route('customer.downloadable_products.index');
        }

        if ($downloadableLinkPurchased->download_bought
            && ($downloadableLinkPurchased->download_bought - ($downloadableLinkPurchased->download_used + $downloadableLinkPurchased->download_canceled)) <= 0) {

            session()->flash('warning', trans('shop::app.customer.account.downloadable_products.download-error'));

            return redirect()->route('customer.downloadable_products.index');
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
            $fileName = $name = substr($downloadableLinkPurchased->url, strrpos($downloadableLinkPurchased->url, '/') + 1);;

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($downloadableLinkPurchased->url, $tempImage);

            return response()->download($tempImage, $fileName);
        }
    }
}