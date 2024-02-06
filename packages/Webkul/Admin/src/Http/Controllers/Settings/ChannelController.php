<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Settings\ChannelDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\ChannelRepository;

class ChannelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ChannelRepository $channelRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ChannelDataGrid::class)->toJson();
        }

        return view('admin::settings.channels.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::settings.channels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = $this->validate(request(), [
            /* general */
            'code'                  => ['required', 'unique:channels,code', new \Webkul\Core\Rules\Code],
            'name'                  => 'required',
            'description'           => 'nullable',
            'inventory_sources'     => 'required|array|min:1',
            'root_category_id'      => 'required',
            'hostname'              => 'unique:channels,hostname',

            /* currencies and locales */
            'locales'               => 'required|array|min:1',
            'default_locale_id'     => 'required|in_array:locales.*',
            'currencies'            => 'required|array|min:1',
            'base_currency_id'      => 'required|in_array:currencies.*',

            /* design */
            'theme'                 => 'nullable',
            'logo.*'                => 'nullable|mimes:bmp,jpeg,jpg,png,webp',
            'favicon.*'             => 'nullable|mimes:bmp,jpeg,jpg,png,webp,ico',

            /* seo */
            'seo_title'             => 'required|string',
            'seo_description'       => 'required|string',
            'seo_keywords'          => 'required|string',

            /* maintenance mode */
            'is_maintenance_on'     => 'boolean',
            'maintenance_mode_text' => 'nullable',
            'allowed_ips'           => 'nullable',
        ]);

        $data = $this->setSEOContent($data);

        Event::dispatch('core.channel.create.before');

        $channel = $this->channelRepository->create($data);

        Event::dispatch('core.channel.create.after', $channel);

        session()->flash('success', trans('admin::app.settings.channels.create.create-success'));

        return redirect()->route('admin.settings.channels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $channel = $this->channelRepository->with(['locales', 'currencies'])->findOrFail($id);

        return view('admin::settings.channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $locale = core()->getRequestedLocaleCode();

        $data = $this->validate(request(), [
            /* general */
            'code'                             => ['required', 'unique:channels,code,'.$id, new \Webkul\Core\Rules\Code],
            $locale.'.name'                    => 'required',
            $locale.'.description'             => 'nullable',
            'inventory_sources'                => 'required|array|min:1',
            'root_category_id'                 => 'required',
            'hostname'                         => 'unique:channels,hostname,'.$id,

            /* currencies and locales */
            'locales'                          => 'required|array|min:1',
            'default_locale_id'                => 'required|in_array:locales.*',
            'currencies'                       => 'required|array|min:1',
            'base_currency_id'                 => 'required|in_array:currencies.*',

            /* design */
            'theme'                            => 'nullable',
            'logo.*'                           => 'nullable|mimes:bmp,jpeg,jpg,png,webp',
            'favicon.*'                        => 'nullable|mimes:bmp,jpeg,jpg,png,webp,ico',

            /* seo */
            $locale.'.seo_title'               => 'required|string',
            $locale.'.seo_description'         => 'required|string',
            $locale.'.seo_keywords'            => 'required|string',

            /* maintenance mode */
            'is_maintenance_on'                => 'boolean',
            $locale.'.maintenance_mode_text'   => 'nullable',
            'allowed_ips'                      => 'nullable',
        ]);

        $data['is_maintenance_on'] = request()->input('is_maintenance_on') == '1';

        $data = $this->setSEOContent($data, $locale);

        Event::dispatch('core.channel.update.before', $id);

        $channel = $this->channelRepository->update($data, $id);

        Event::dispatch('core.channel.update.after', $channel);

        if ($channel->base_currency->code !== session()->get('currency')) {
            session()->put('currency', $channel->base_currency->code);
        }

        session()->flash('success', trans('admin::app.settings.channels.edit.update-success'));

        return redirect()->route('admin.settings.channels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $channel = $this->channelRepository->findOrFail($id);

        if ($channel->code == config('app.channel')) {
            return new JsonResponse([
                'message'    => trans('admin::app.settings.channels.index.last-delete-error'),
            ], 400);
        }

        try {
            Event::dispatch('core.channel.delete.before', $id);

            $this->channelRepository->delete($id);

            Event::dispatch('core.channel.delete.after', $id);

            return new JsonResponse([
                'message'    => trans('admin::app.settings.channels.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message'    => trans('admin::app.settings.channels.index.delete-failed'),
        ], 500);
    }

    /**
     * Set the seo content and return back the updated array.
     *
     * @param  string  $locale
     * @return array
     */
    private function setSEOContent(array $data, $locale = null)
    {
        $editedData = $data;

        if ($locale) {
            $editedData = $data[$locale];
        }

        $editedData['home_seo']['meta_title'] = $editedData['seo_title'];
        $editedData['home_seo']['meta_description'] = $editedData['seo_description'];
        $editedData['home_seo']['meta_keywords'] = $editedData['seo_keywords'];

        $editedData = $this->unsetKeys($editedData, ['seo_title', 'seo_description', 'seo_keywords']);

        if ($locale) {
            $data[$locale] = $editedData;
            $editedData = $data;
        }

        return $editedData;
    }

    /**
     * Unset keys.
     *
     * @param  array  $keys
     * @return array
     */
    private function unsetKeys($data, $keys)
    {
        foreach ($keys as $key) {
            unset($data[$key]);
        }

        return $data;
    }
}
