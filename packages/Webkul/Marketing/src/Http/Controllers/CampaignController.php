<?php

namespace Webkul\Marketing\Http\Controllers;

use Webkul\Admin\DataGrids\CampaignDataGrid;
use Webkul\Marketing\Repositories\CampaignRepository;

class CampaignController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Campaign repository instance.
     *
     * @var \Webkul\Marketing\Repositories\CampaignRepository
     */
    protected $campaignRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketing\Repositories\CampaignRepository  $campaignRepository
     * @return void
     */
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;

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
        return view($this->_config['view']);
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

        $this->campaignRepository->create(request()->all());

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

        return view($this->_config['view'], compact('campaign'));
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

        $this->campaignRepository->update(request()->all(), $id);

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
            $this->campaignRepository->delete($id);

            return response()->json(['message' => trans('admin::app.marketing.campaigns.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Email Campaign'])], 500);
    }
}
