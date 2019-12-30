<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use DB;
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
        if (! request()->get('slider')) {
            $params = request()->all() + [
                'slider' => 0
            ];
        } else {
            $params = request()->all();
        }

        $product = $this->velocityMetaDataRepository->update($params, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Velocity Theme']));

        return redirect()->route($this->_config['redirect']);
    }
}