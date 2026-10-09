<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Sales\Contracts\DownloadableLinkPurchased;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;
use Webkul\Shop\DataGrids\DownloadableProductDataGrid;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Traits\ValidatesExternalUrl;

class DownloadableProductController extends Controller
{
    use ValidatesExternalUrl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(DownloadableProductDataGrid::class)->process();
        }

        return view('shop::customers.account.downloadable-products.index');
    }

    /**
     * Serve the file or url a purchased downloadable link points to, spending one download.
     *
     * @param  int  $id
     * @return Response
     */
    public function download($id)
    {
        $downloadableLinkPurchased = $this->downloadableLinkPurchasedRepository->findOneByField([
            'id' => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if (! $downloadableLinkPurchased) {
            abort(404);
        }

        if ($downloadableLinkPurchased->status == 'pending') {
            abort(404);
        }

        abort_unless($this->isDeliverable($downloadableLinkPurchased), 404);

        if (! $this->downloadableLinkPurchasedRepository->consumeDownload($downloadableLinkPurchased->id)) {
            session()->flash('warning', trans('shop::app.customers.account.downloadable-products.download-error'));

            return redirect()->route('shop.customers.account.downloadable_products.index');
        }

        return $this->serveLink($downloadableLinkPurchased);
    }

    /**
     * Can the purchased link be delivered at all? Answered before a download is spent, so a
     * missing file or a refused url does not cost the customer one of the downloads they bought.
     *
     * @param  DownloadableLinkPurchased  $purchasedLink
     */
    protected function isDeliverable($purchasedLink): bool
    {
        if ($purchasedLink->type == 'file') {
            return Storage::disk('private')->exists($purchasedLink->file);
        }

        return $this->validateExternalUrl($purchasedLink->url);
    }

    /**
     * Send the purchased link's file to the customer.
     *
     * @param  DownloadableLinkPurchased  $purchasedLink
     * @return Response
     */
    protected function serveLink($purchasedLink)
    {
        if ($purchasedLink->type == 'file') {
            return Storage::disk('private')->download($purchasedLink->file);
        }

        $fileName = substr($purchasedLink->url, strrpos($purchasedLink->url, '/') + 1);

        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        copy($purchasedLink->url, $tempFile);

        return response()->download($tempFile, $fileName);
    }
}
