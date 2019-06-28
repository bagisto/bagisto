<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $productRepository;

    /**
     * ProductDownloadableSampleRepository object
     *
     * @var array
     */
    protected $productDownloadableSampleRepository;

    /**
     * ProductDownloadableLinkRepository object
     *
     * @var array
     */
    protected $productDownloadableLinkRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository                   $productRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableSampleRepository $productDownloadableSampleRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableLinkRepository   $productDownloadableLinkRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository
    )
    {
        $this->productRepository = $productRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $product = $this->productRepository->findBySlugOrFail($slug);

        $customer = auth()->guard('customer')->user();

        return view($this->_config['view'], compact('product','customer'));
    }

    /**
     * Download the for the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadSample()
    {
        if (request('type') == 'link') {
            $productDownloadableLink = $this->productDownloadableLinkRepository->findOrFail(request('id'));

            if ($productDownloadableLink->sample_type == 'file')
                return Storage::download($productDownloadableLink->sample_file);
            else {
                $fileName = $name = substr($productDownloadableLink->sample_url, strrpos($productDownloadableLink->sample_url, '/') + 1);

                $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                copy($productDownloadableLink->sample_url, $tempImage);

                return response()->download($tempImage, $fileName);
            }
        } else {
            $productDownloadableSample = $this->productDownloadableSampleRepository->findOrFail(request('id'));

            if ($productDownloadableSample->type == 'file')
                return Storage::download($productDownloadableSample->file);
            else {
                $fileName = $name = substr($productDownloadableSample->url, strrpos($productDownloadableSample->url, '/') + 1);

                $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                copy($productDownloadableSample->url, $tempImage);

                return response()->download($tempImage, $fileName);
            }
        }
    }
}
