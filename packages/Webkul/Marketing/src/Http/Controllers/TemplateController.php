<?php

namespace Webkul\Marketing\Http\Controllers;

use Webkul\Admin\DataGrids\EmailTemplateDataGrid;
use Webkul\Marketing\Repositories\TemplateRepository;

class TemplateController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Template repository instance.
     *
     * @var \Webkul\Marketing\Repositories\TemplateRepository
     */
    protected $templateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketing\Repositories\TemplateRepository  $templateRepository
     * @return void
     */
    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;

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
            return app(EmailTemplateDataGrid::class)->toJson();
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
            'name'    => 'required',
            'status'  => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        $this->templateRepository->create(request()->all());

        session()->flash('success', trans('admin::app.marketing.templates.create-success'));

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
        $template = $this->templateRepository->findOrFail($id);

        return view($this->_config['view'], compact('template'));
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
            'name'    => 'required',
            'status'  => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        $this->templateRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.marketing.templates.update-success'));

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
        $this->templateRepository->findOrFail($id);

        try {
            $this->templateRepository->delete($id);

            return response()->json(['message' => trans('admin::app.marketing.templates.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Email Template'])], 400);
    }
}
