<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Facades\Configuration;
use Webkul\Core\Repositories\CoreConfigRepository as CoreConfig;
use Webkul\Core\Tree;
use Webkul\Admin\Http\Requests\ConfigurationForm;

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
     *
     * @var array
     */
    protected $configTree;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(CoreConfig $coreConfig)
    {
        $this->middleware('admin');

        $this->coreConfig = $coreConfig;

        $this->_config = request('_config');

        $this->prepareConfigTree();

    }

    /**
     * Prepares config tree
     *
     * @return void
     */
    public function prepareConfigTree()
    {
        $tree = Tree::create();

        foreach (config('core') as $item) {
            $tree->add($item);
        }

        $tree->items = core()->sortItems($tree->items);

        $this->configTree = $tree;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slugs = $this->getDefaultConfigSlugs();

        if (count($slugs)) {
            return redirect()->route('admin.configuration.index', $slugs);
        }

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    /**
     * Returns slugs
     *
     * @return array
     */
    public function getDefaultConfigSlugs()
    {
        $slugs = [];

        if (! request()->route('slug')) {
            $firstItem = current($this->configTree->items);
            $secondItem = current($firstItem['children']);

            $temp = explode('.', $secondItem['key']);

            $slugs = [
                'slug' => current($temp),
                'slug2' => end($temp)
            ];
        } else {
            if (! request()->route('slug2')) {
                $secondItem = current($this->configTree->items[request()->route('slug')]['children']);

                $temp = explode('.', $secondItem['key']);

                $slugs = [
                    'slug' => current($temp),
                    'slug2' => end($temp)
                ];
            }
        }

        return $slugs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Admin\Http\Requests\ConfigurationForm $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Event::fire('core.configuration.save.after');

        $this->coreConfig->create(request()->all());

        Event::fire('core.configuration.save.after');

        session()->flash('success', 'Shipping Method is created successfully');

        return redirect()->back();
    }
}