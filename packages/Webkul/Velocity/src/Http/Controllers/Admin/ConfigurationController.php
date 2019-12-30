<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use DB;
use Webkul\Velocity\Repositories\MetadataRepository;

/**
 * Category Controller
 *
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ConfigurationController extends Controller
{
    /**
     * MetadataRepository object
     *
     * @var Object
     */
    protected $metaDataRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Repositories\MetadataRepository $metaDataRepository
     */

    public function __construct ()
    {
        $this->_config = request('_config');
    }

    public function storeMetaData()
    {
        $metaData = DB::table('velocity_meta_data')->get();

        if (! ($metaData && ($metaData = $metaData[0]))) {
            $metaData = null;
        }

        return view($this->_config['view'], compact('metaData'));
    }
}