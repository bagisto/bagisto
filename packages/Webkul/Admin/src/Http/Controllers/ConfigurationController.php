<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Tree;
use Illuminate\Support\Facades\Storage;

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
    protected $coreConfigRepository;

    /**
     *
     * @var array
     */
    protected $configTree;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository $coreConfigRepository
     * @return void
     */
    public function __construct(CoreConfigRepository $coreConfigRepository)
    {
        $this->middleware('admin');

        $this->coreConfigRepository = $coreConfigRepository;

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
     * @return \Illuminate\View\View
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
        if (! request()->route('slug')) {
            $firstItem = current($this->configTree->items);
            $secondItem = current($firstItem['children']);

            return $this->getSlugs($secondItem);
        }

        if (! request()->route('slug2')) {
            $secondItem = current($this->configTree->items[request()->route('slug')]['children']);

            return $this->getSlugs($secondItem);
        }

        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Admin\Http\Requests\ConfigurationForm $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Event::fire('core.configuration.save.before');

        if (request()->hasFile('general.design.admin_logo.logo_image')) {
            $this->validate(request(), [
                'general.design.admin_logo.logo_image'  => 'required|mimes:jpeg,bmp,png,jpg'
            ]);
        }

        $this->coreConfigRepository->create(request()->all());

        Event::fire('core.configuration.save.after');

        session()->flash('success', trans('admin::app.configuration.save-message'));

        return redirect()->back();
    }

    /**
     * download the file for the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $path = request()->route()->parameters()['path'];

        $fileName = 'configuration/'. $path;

        $config = $this->coreConfigRepository->findOneByField('value', $fileName);

        return Storage::download($config['value']);
    }

    /**
     * @param $secondItem
     *
     * @return array
     */
    private function getSlugs($secondItem): array
    {
        $temp = explode('.', $secondItem['key']);

        return ['slug' => current($temp), 'slug2' => end($temp)];
    }
}