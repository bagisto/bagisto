<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Velocity\Repositories\MetadataRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

/**
 * Category Controller
 *
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
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

        $this->velocityMetaDataRepository = $velocityMetadataRepository;
    }

    public function renderMetaData()
    {
        $velocityHelper = app('Webkul\Velocity\Helpers\Helper');
        $velocityMetaData = $velocityHelper->getVelocityMetaData();

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

        $params['advertisement'] = [];

        foreach ($params['images'] as $index => $advertisement) {
            if ($advertisement['image_1'] !== "") {
                $params['advertisement'][$index] = $this->uploadImages($advertisement, $index);
            }
        }

        $params['advertisement'] = json_encode($params['advertisement']);

        unset($params['images']);
        unset($params['slides']);

        // update row
        $product = $this->velocityMetaDataRepository->update($params, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Velocity Theme']));

        return redirect()->route($this->_config['redirect']);
    }

    public function uploadImages($data, $index)
    {
        $type = 'images';
        $request = request();

        foreach ($data as $imageId => $image) {
            $file = $type . '.' . $index . '.' . $imageId;
            $dir = "velocity/$type";

            if ($request->hasFile($file)) {
                Storage::delete($dir . $file);

                $advertisement[$imageId] = $request->file($file)->store($dir);
            }
        }

        return $advertisement;
    }
}