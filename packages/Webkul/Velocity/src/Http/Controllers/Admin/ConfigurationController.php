<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

/**
 * Category Controller
 *
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @author    Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ConfigurationController extends Controller
{
    /**
     * VelocityMetadataRepository object
     *
     * @var Object
     */
    protected $velocityMetaDataRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Repositories\MetadataRepository $metaDataRepository
     */

    public function __construct (
        VelocityMetadataRepository $velocityMetadataRepository
    ) {
        $this->_config = request('_config');
        
        $this->velocityHelper = app('Webkul\Velocity\Helpers\Helper');

        $this->velocityMetaDataRepository = $velocityMetadataRepository;
    }

    public function renderMetaData()
    {
        $velocityMetaData = $this->velocityHelper->getVelocityMetaData();

        $velocityMetaData->advertisement = $this->manageAddImages(json_decode($velocityMetaData->advertisement, true));

        return view($this->_config['view'], [
            'metaData' => $velocityMetaData
        ]);
    }

    public function storeMetaData($id)
    {
        // check if radio button value
        if (request()->get('slides') == "on") {
            $params = request()->all() + [
                'slider' => 1
            ];
        } else {
            $params = request()->all() + [
                'slider' => 0
            ];
        }

        $velocityMetaData = $this->velocityMetaDataRepository->findorFail($id);

        $advertisement = json_decode($velocityMetaData->advertisement, true);

        $params['advertisement'] = [];

        if ( isset($params['images'])) {
            foreach ($params['images'] as $index => $images) {
                $params['advertisement'][$index] =  $this->uploadAdvertisementImages($images, $index, $advertisement);
            }
        }

        if (isset($params['product_view_images'])) {
            foreach ($params['product_view_images'] as $index => $productViewImage) {
                if ($productViewImage !== "") {
                    $params['product_view_images'][$index] = $this->uploadImage($productViewImage, $index);
                }
            }

            $params['product_view_images'] = json_encode($params['product_view_images']);
        }

        $params['advertisement'] = json_encode($params['advertisement']);
        $params['home_page_content'] = str_replace('=&gt;', '=>', $params['home_page_content']);

        unset($params['images']);
        unset($params['slides']);

        // update row
        $product = $this->velocityMetaDataRepository->update($params, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Velocity Theme']));

        return redirect()->route($this->_config['redirect']);
    }

    public function uploadAdvertisementImages($data, $index, $advertisement)
    {
        $save_image = [];

        foreach ($data as $imageId => $image) {
            $file = 'images.' . $index . '.' . $imageId;
            $dir = 'velocity/images';

            if (Str::contains($imageId, 'image_')) {
                if (request()->hasFile($file) && $image) {
                    Storage::delete($dir . $file);

                    $save_image[substr($imageId, 6, 1)] = request()->file($file)->store($dir);
                }
            } else {
                if ( isset($advertisement[$index][$imageId]) && $advertisement[$index][$imageId]) {
                    $save_image[$imageId] = $advertisement[$index][$imageId];
                    unset($advertisement[$index][$imageId]);
                }
                
                if (request()->hasFile($file) && isset($advertisement[$index][$imageId])) {
                    Storage::delete($advertisement[$index][$imageId]);

                    $save_image[$imageId] = request()->file($file)->store($dir);
                }
            }
        }

        if ( isset($advertisement[$index]) && $advertisement[$index]) {
            foreach ($advertisement[$index] as $imageId) {
                Storage::delete($imageId);
                $save_image[$imageId] = '';
            }
        }

        return $save_image;
    }

    public function uploadImage($data, $index)
    {
        $type = 'product_view_images';
        $request = request();

        $image = '';
        $file = $type . '.' . $index;
        $dir = "velocity/$type";

        if ($request->hasFile($file)) {
            Storage::delete($dir . $file);

            $image = $request->file($file)->store($dir);
        }

        return $image;
    }

    public function manageAddImages($add_images)
    {
        $images_path = [];
        foreach ($add_images as $add_id => $images) {
            foreach ($images as $key => $image) {
                if ( $image ) {
                    $images_path[$add_id][] = [
                        'id' => $key,
                        'type' => null,
                        'path' => $image,
                        'url' => Storage::url($image)
                    ];
                }
            }
        }
        
        return $images_path;
    }
}