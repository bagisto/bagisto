<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Admin\Http\Requests\ConfigurationForm;
use Webkul\Core\Repositories\CoreConfigRepository;

class ConfigurationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if (
            request()->route('slug')
            && request()->route('slug2')
        ) {
            return view('admin::configuration.edit');
        }

        return view('admin::configuration.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function search(): JsonResponse
    {
        $results = $this->coreConfigRepository->search(
            system_config()->getItems(),
            request()->query('query')
        );

        return new JsonResponse([
            'data' => $results,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConfigurationForm $request): RedirectResponse
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
                session()->flash('error', trans('admin::app.configuration.index.enable-at-least-one-payment'));

                return redirect()->back();
            }
        }

        $this->coreConfigRepository->create($request->except(['_token', 'admin_locale']));

        session()->flash('success', trans('admin::app.configuration.index.save-message'));

        return redirect()->back();
    }

    /**
     * Download the file for the specified resource.
     */
    public function download(): StreamedResponse
    {
        $path = request()->route()->parameters()['path'];

        $fileName = 'configuration/'.$path;

        $config = $this->coreConfigRepository->findOneByField('value', $fileName);

        return Storage::download($config['value']);
    }
}
