<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
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

        if (! $this->downloadableLinkPurchasedRepository->consumeDownload($downloadableLinkPurchased->id)) {
            session()->flash('warning', trans('shop::app.customers.account.downloadable-products.download-error'));

            return redirect()->route('shop.customers.account.downloadable_products.index');
        }

        if ($downloadableLinkPurchased->type == 'file') {
            $privateDisk = Storage::disk('private');

            return $privateDisk->exists($downloadableLinkPurchased->file)
                ? $privateDisk->download($downloadableLinkPurchased->file)
                : abort(404);
        } else {
            if (! $this->validateExternalUrl($downloadableLinkPurchased->url)) {
                abort(404);
            }

            $fileName = $name = substr($downloadableLinkPurchased->url, strrpos($downloadableLinkPurchased->url, '/') + 1);

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($downloadableLinkPurchased->url, $tempImage);

            return response()->download($tempImage, $fileName);
        }
    }
}
