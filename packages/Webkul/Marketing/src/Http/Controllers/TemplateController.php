<?php

namespace Webkul\Marketing\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Marketing\Repositories\TemplateRepository;

class TemplateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * TemplateRepository object
     *
     * @var \Webkul\Core\Repositories\TemplateRepository
     */
    protected $templateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\TemplateRepository  $templateRepository
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

        Event::dispatch('marketing.templates.create.before');

        $locale = $this->templateRepository->create(request()->all());

        Event::dispatch('marketing.templates.create.after', $locale);

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

        Event::dispatch('marketing.templates.update.before', $id);

        $locale = $this->templateRepository->update(request()->all(), $id);

        Event::dispatch('marketing.templates.update.after', $locale);

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
        $locale = $this->templateRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.templates.delete.before', $id);

            $this->templateRepository->delete($id);

            Event::dispatch('marketing.templates.delete.after', $id);

            session()->flash('success', trans('admin::app.marketing.templates.delete-success'));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Email Template']));
        }

        return response()->json(['message' => false], 400);
    }
}