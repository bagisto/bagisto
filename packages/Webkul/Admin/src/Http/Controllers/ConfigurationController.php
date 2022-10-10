<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Admin\Http\Requests\ConfigurationForm;
use Webkul\Core\Tree;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;

    /**
     * Tree instance.
     *
     * @var \Webkul\Core\Tree
     */
    protected $configTree;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfigRepository
     * @return void
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository)
    {
        $this->_config = request('_config');

        $this->prepareConfigTree();
    }

    /**
     * Prepares config tree.
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
     * Returns slugs.
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
     * @param  \Webkul\Admin\Http\Requests\ConfigurationForm  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ConfigurationForm $request)
    {
        $data = $request->request->all();
        
        if (isset($data['sales']['carriers'])) {
            $atLeastOneCarrierEnabled = false;
            
            foreach ($data['sales']['carriers'] as $carrier) {
                if ($carrier['active']) {
                    $atLeastOneCarrierEnabled = true;
                    
                    break;
                }
            }
            
            if (! $atLeastOneCarrierEnabled) {
                session()->flash('error', trans('admin::app.configuration.enable-atleast-one-shipping'));
                
                return redirect()->back();
            }
        } elseif (isset($data['sales']['paymentmethods'])) {
            $atLeastOnePaymentMethodEnabled = false;
            
            foreach ($data['sales']['paymentmethods'] as $paymentMethod) {
                if ($paymentMethod['active']) {
                    $atLeastOnePaymentMethodEnabled = true;

                    break;
                }
            }
            
            if (! $atLeastOnePaymentMethodEnabled) {
                session()->flash('error', trans('admin::app.configuration.enable-atleast-one-payment'));
                
                return redirect()->back();
            }
        }

        $this->coreConfigRepository->create($request->except(['_token', 'admin_locale']));

        session()->flash('success', trans('admin::app.configuration.save-message'));

        return redirect()->back();
    }

    /**
     * Download the file for the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $path = request()->route()->parameters()['path'];

        $fileName = 'configuration/' . $path;

        $config = $this->coreConfigRepository->findOneByField('value', $fileName);

        return Storage::download($config['value']);
    }

    /**
     * Get slugs.
     *
     * @param  string  $secondItem
     * @return array
     */
    private function getSlugs($secondItem): array
    {
        $temp = explode('.', $secondItem['key']);

        return ['slug' => current($temp), 'slug2' => end($temp)];
    }
}
