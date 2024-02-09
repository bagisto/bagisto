<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Requests\ConfigurationForm;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Tree;

class ConfigurationController extends Controller
{
    /**
     * Tree instance.
     *
     * @var \Webkul\Core\Tree
     */
    protected $configTree;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository)
    {
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
        $groups = Arr::get(
            $this->configTree->items,
            request()->route('slug').'.children.'.request()->route('slug2').'.children'
        );

        if ($groups) {
            return view('admin::configuration.edit', [
                'config' => $this->configTree,
                'groups' => $groups,
            ]);
        }

        return view('admin::configuration.index', ['config' => $this->configTree]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $results = $this->coreConfigRepository->search($this->configTree->items, request()->query('query'));

        return new JsonResponse([
            'data' => $results,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ConfigurationForm $request)
    {
        $data = $request->all();

        if (isset($data['sales']['carriers'])) {
            $atLeastOneCarrierEnabled = false;

            foreach ($data['sales']['carriers'] as $carrier) {
                if ($carrier['active']) {
                    $atLeastOneCarrierEnabled = true;

                    break;
                }
            }

            if (! $atLeastOneCarrierEnabled) {
                session()->flash('error', trans('admin::app.configuration.index.enable-at-least-one-shipping'));

                return redirect()->back();
            }
        } elseif (isset($data['sales']['payment_methods'])) {
            $atLeastOnePaymentMethodEnabled = false;

            foreach ($data['sales']['payment_methods'] as $paymentMethod) {
                if ($paymentMethod['active']) {
                    $atLeastOnePaymentMethodEnabled = true;

                    break;
                }
            }

            if (! $atLeastOnePaymentMethodEnabled) {
                session()->flash('error', trans('admin::app.configuration.enable-at-least-one-payment'));

                return redirect()->back();
            }
        }

        $this->coreConfigRepository->create($request->except(['_token', 'admin_locale']));

        session()->flash('success', trans('admin::app.configuration.index.save-message'));

        return redirect()->back();
    }

    /**
     * Download the file for the specified resource.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download()
    {
        $path = request()->route()->parameters()['path'];

        $fileName = 'configuration/'.$path;

        $config = $this->coreConfigRepository->findOneByField('value', $fileName);

        return Storage::download($config['value']);
    }
}
