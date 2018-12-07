<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Facades\Configuration;
use Webkul\Core\Repositories\CoreConfigRepository as CoreConfig;

/**
 * Configuration controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * CoreConfigRepository object
     *
     * @var array
     */
    protected $coreConfig;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(CoreConfig $coreConfig)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->coreConfig = $coreConfig;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingMethods = Configuration::getShippingMethod();

        return view($this->_config['view'], compact('shippingMethods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        unset($data['_token']);

        if($data['locale'] || $data['channel']){
            $locale = $data['locale'];
            $channel = $data['channel'];
            unset($data['locale']);
            unset($data['channel']);
        }

        foreach($data as $key => $value) {

            $reomoveUnderScore = explode ("_", $key);

            $key= implode(".",$reomoveUnderScore);

            $fieldName = end($reomoveUnderScore);

            array_pop($reomoveUnderScore);

            $fieldCode = end($reomoveUnderScore);

            foreach(Configuration::getShippingMethod() as $fields) {
                $code = $fields->getCode();

                if($code == $fieldCode) {
                    $field = $fields->getFieldDetails($fieldName);
                }
            }

            $channel_based = false;
            $locale_based = false;

            if(isset($field['channel_based']) && $field['channel_based']) {
                $channel_based = true;
            }

            if(isset($field['locale_based']) && $field['locale_based']) {
                $locale_based = true;
            }

            $coreConfigValue = $this->coreConfig->findOneWhere([
                'code' => $key,
                'value' => $value,
                'locale_code' => $locale_based ? $locale : null,
                'channel_code' => $channel_based ? $channel : null
            ]);

            if(!$coreConfigValue) {
                $this->coreConfig->create([
                    'code' => $key,
                    'value' => $value,
                    'locale_code' => $locale_based ? $locale : null,
                    'channel_code' => $channel_based ? $channel : null
                ]);
            }else {
                $updataData['code'] = $key;
                $updataData['value'] = $value;
                $updataData['locale_code'] = $locale_based ? $locale : null;
                $updataData['channel_code'] = $channel_based ? $channel : null;

                $this->coreConfig->update($updataData, $coreConfigValue->id);
            }
        }

        session()->flash('success', 'Shipping Method is created successfully');

        return redirect()->route($this->_config['redirect']);
    }
}