<?php

namespace Webkul\Marketing\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\CampaignDataGrid;
use Webkul\Marketing\Repositories\CampaignRepository;
use Webkul\Marketing\Repositories\TemplateRepository;

class CampaignController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketing\Repositories\CampaignRepository  $campaignRepository
     * @param  \Webkul\Marketing\Repositories\TemplateRepository  $templateRepository
     * @return void
     */
    public function __construct(
        protected CampaignRepository $campaignRepository,
        protected TemplateRepository $templateRepository,
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CampaignDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $templates = $this->templateRepository->findByField('status', 'active');

        return view($this->_config['view'], compact('templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'                  => 'required',
            'subject'               => 'required',
            'status'                => 'required',
            'marketing_template_id' => 'required',
            'marketing_event_id'    => 'required_if:schedule_type,event',
        ]);

        Event::dispatch('marketing.campaigns.create.before');

        $campaign = $this->campaignRepository->create(request()->all());

        Event::dispatch('marketing.campaigns.create.after', $campaign);

        session()->flash('success', trans('admin::app.marketing.campaigns.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $templates = $this->templateRepository->findByField('status', 'active');

        return view($this->_config['view'], compact('campaign', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name'                  => 'required',
            'subject'               => 'required',
            'status'                => 'required',
            'marketing_template_id' => 'required',
            'marketing_event_id'    => 'required_if:schedule_type,event',
        ]);

        Event::dispatch('marketing.campaigns.update.before', $id);

        $campaign = $this->campaignRepository->update(request()->all(), $id);

        Event::dispatch('marketing.campaigns.update.after', $campaign);

        session()->flash('success', trans('admin::app.marketing.campaigns.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->campaignRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.campaigns.delete.before', $id);

            $this->campaignRepository->delete($id);

            Event::dispatch('marketing.campaigns.delete.after', $id);

            return response()->json(['message' => trans('admin::app.marketing.campaigns.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Email Campaign'])], 500);
    }
}
