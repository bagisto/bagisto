<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Webkul\Core\Traits\Sanitizer;
use Webkul\Velocity\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

class ConfigurationController extends Controller
{
    use Sanitizer;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper  $velocityHelper
     * @param  \Webkul\Velocity\Repositories\MetadataRepository  $velocityMetadataRepository
     * @return void
     */
    public function __construct (
        protected Helper $velocityHelper,
        protected VelocityMetadataRepository $velocityMetadataRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Render meta data.
     *
     * @return \Illuminate\View\View
     */
    public function renderMetaData()
    {
        $locale = core()->checkRequestedLocaleCodeInRequestedChannel();

        $channel = core()->getRequestedChannelCode();

        $velocityMetaData = $this->velocityHelper->getVelocityMetaData($locale, $channel, false);

        if (! $velocityMetaData) {
            $this->createMetaData($locale, $channel);

            $velocityMetaData = $this->velocityHelper->getVelocityMetaData($locale, $channel);
        }

        $velocityMetaData->advertisement = $this->manageAddImages(json_decode($velocityMetaData->advertisement, true) ?: []);

        return view($this->_config['view'], [
            'metaData' => $velocityMetaData,
        ]);
    }

    /**
     * Store meta data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeMetaData($id)
    {
        $locale = core()->checkRequestedLocaleCodeInRequestedChannel();

        /* check if radio button value */
        if (request()->get('slides') == "on") {
            $params = request()->all() + [
                'slider' => 1,
            ];
        } else {
            $params = request()->all() + [
                'slider' => 0,
            ];
        }

        $velocityMetaData = $this->velocityMetadataRepository->findOneWhere([
            'id' => $id,
        ]);

        $advertisement = json_decode($velocityMetaData->advertisement, true);

        $params['advertisement'] = [];

        if (isset($params['images'])) {
            foreach ($params['images'] as $index => $images) {
                $params['advertisement'][$index] =  $this->uploadAdvertisementImages($images, $index, $advertisement);
            }

            if ($advertisement) {
                foreach ($advertisement as $key => $image_array) {
                    if (! isset($params['images'][$key])) {
                        foreach ($advertisement[$key] as $image) {
                            Storage::delete($image);
                        }
                    }
                }
            }
        }

        $params['advertisement'] = json_encode($params['advertisement']);
        $params['home_page_content'] = str_replace('=&gt;', '=>', $params['home_page_content']);

        unset($params['images']);
        unset($params['slides']);

        $params['locale'] = $locale;

        /* update row */
        $this->velocityMetadataRepository->update($params, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => trans('velocity::app.admin.meta-data.title')]));

        return redirect()->route($this->_config['redirect'], ['locale' => $locale]);
    }

    /**
     * Upload advertisement images.
     *
     * @param  array    $data
     * @param  int      $index
     * @param  array    $advertisement
     * @return array
     */
    public function uploadAdvertisementImages($data, $index, $advertisement)
    {
        $saveImage = [];
        $dir = 'velocity/images';

        $saveData = $advertisement;

        foreach ($data as $imageId => $image) {
            if ($image != "") {
                $file = 'images.' . $index . '.' . $imageId;

                if (Str::contains($imageId, 'image_')) {
                    if (
                        request()->hasFile($file)
                        && $image
                    ) {
                        $filter_index = substr($imageId, 6, 1);
                        if (isset($data[$filter_index])) {
                            $size = array_key_last($saveData[$index]);

                            $saveImage[$size + 1] = $path = request()->file($file)->store($dir);
                        } else {
                            $saveImage[substr($imageId, 6, 1)] = $path = request()->file($file)->store($dir);
                        }

                        $this->sanitizeSVG($path, $image->getMimeType());
                    }
                } else {
                    if (
                        isset($advertisement[$index][$imageId])
                        && $advertisement[$index][$imageId]
                        && !request()->hasFile($file)
                    ) {
                        $saveImage[$imageId] = $advertisement[$index][$imageId];

                        unset($advertisement[$index][$imageId]);
                    }

                    if (
                        request()->hasFile($file)
                        && isset($advertisement[$index][$imageId])
                    ) {
                        Storage::delete($advertisement[$index][$imageId]);

                        $saveImage[$imageId] = request()->file($file)->store($dir);
                    }
                }
            } else {
                $subIndex = substr($imageId, -1);

                if ($saveData) {
                    if (isset($advertisement[$index][$subIndex])) {
                        $saveImage[$subIndex] = $advertisement[$index][$subIndex];

                        if (sizeof($advertisement[$index]) == 1) {
                            unset($advertisement[$index]);
                        } else {
                            unset($advertisement[$index][$subIndex]);
                        }
                    }
                } else {
                    if (! isset($advertisement[$index][$subIndex])) {
                        if ( $index == 4 ) {
                            switch ($subIndex) {
                                case '1':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/big-sale-banner.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '2':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/seasons.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '3':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/deals.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '4':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/kids.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;

                                default:

                                    break;
                            }
                        } elseif ( $index == 3 ) {
                            switch ($subIndex) {
                                case '1':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/headphones.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '2':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/watch.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '3':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/kids-2.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;

                                default:

                                    break;
                            }
                        } elseif ( $index == 2 ) {
                            switch ($subIndex) {
                                case '1':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/toster.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;
                                case '2':
                                    $copyAdImage = $this->copyAdvertiseImages(public_path('/themes/velocity/assets/images/trimmer.webp'), $dir);
                                    if ( $copyAdImage ) {
                                        $saveImage[$subIndex] = $copyAdImage;
                                    }
                                    break;

                                default:

                                    break;
                            }
                        }
                    }
                }
            }
        }

        if (
            isset($advertisement[$index])
            && $advertisement[$index]
        ) {
            foreach ($advertisement[$index] as $imageId) {
                Storage::delete($imageId);
            }
        }

        return $saveImage;
    }

    /**
     * Copy the default adversise images
     *
     * @param  string  $resourceImagePath
     * @param  string  $copiedPath
     * @return mixed
     */
    private function copyAdvertiseImages($resourceImagePath, $copiedPath)
    {
        $result = null;
        $path = explode("/", $resourceImagePath);

        $image = $copiedPath . '/' . end($path);

        Storage::makeDirectory($copiedPath);

        if (File::copy($resourceImagePath, storage_path('app/public/' . $image))) {
            $result = $image;
        }

        return $result;
    }

    /**
     * Manage add images.
     *
     * @param  array  $addImages
     * @return array
     */
    public function manageAddImages($addImages)
    {
        $imagePaths = [];

        foreach ($addImages as $id => $images) {
            foreach ($images as $key => $image) {
                if ($image) {
                    continue;
                }

                $imagePaths[$id][] = [
                    'id'   => $key,
                    'type' => null,
                    'path' => $image,
                    'url'  => Storage::url($image),
                ];
            }
        }

        return $imagePaths;
    }

    /**
     * Create meta data.
     *
     * @param  string  $locale
     * @param  string  $channel
     * @return array
     */
    private function createMetaData($locale, $channel)
    {
        $this->velocityMetadataRepository->create([
            'locale'                   => $locale,
            'channel'                  => $channel,
            'header_content_count'     => '5',
            'home_page_content'        => "<p>@include('shop::home.advertisements.advertisement-four')@include('shop::home.featured-products') @include('shop::home.product-policy') @include('shop::home.advertisements.advertisement-three') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-two')</p>",
            'footer_left_content'      => __('velocity::app.admin.meta-data.footer-left-raw-content'),
            'footer_middle_content'    => '<div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/about-us\') !!}">About Us</a></li><li><a href="{!! url(\'page/cutomer-service\') !!}">Customer Service</a></li><li><a href="{!! url(\'page/whats-new\') !!}">What&rsquo;s New</a></li><li><a href="{!! url(\'page/contact-us\') !!}">Contact Us </a></li></ul></div><div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/return-policy\') !!}"> Order and Returns </a></li><li><a href="{!! url(\'page/payment-policy\') !!}"> Payment Policy </a></li><li><a href="{!! url(\'page/shipping-policy\') !!}"> Shipping Policy</a></li><li><a href="{!! url(\'page/privacy-policy\') !!}"> Privacy and Cookies Policy </a></li></ul></div>',
            'slider'                   => 1,
            'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://facebook.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://twitter.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://linkedin.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://pintrest.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://youtube.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://instagram.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',
            'product_policy'           => '<div class="row col-12 remove-padding-margin"><div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-van-ship fs40"></i></div> <div class="right"><span class="font-setting fs20">Free Shipping on Order $20 or More</span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Replace &amp; Return Available </span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Exchange and EMI Available </span></div></div></div></div></div>',
        ]);
    }
}
