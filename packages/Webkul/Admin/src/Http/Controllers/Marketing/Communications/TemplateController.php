<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Marketing\Communications\EmailTemplateDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketing\Repositories\TemplateRepository;

class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TemplateRepository $templateRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(EmailTemplateDataGrid::class)->process();
        }

        return view('admin::marketing.communications.templates.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::marketing.communications.templates.create');
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

        $template = $this->templateRepository->create(request()->only([
            'name',
            'status',
            'content',
        ]));

        Event::dispatch('marketing.templates.create.after', $template);

        session()->flash('success', trans('admin::app.marketing.communications.templates.create.create-success'));

        return redirect()->route('admin.marketing.communications.email_templates.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $template = $this->templateRepository->findOrFail($id);

        return view('admin::marketing.communications.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name'    => 'required',
            'status'  => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        Event::dispatch('marketing.templates.update.before', $id);

        $template = $this->templateRepository->update(request()->only([
            'name',
            'status',
            'content',
        ]), $id);

        Event::dispatch('marketing.templates.update.after', $template);

        session()->flash('success', trans('admin::app.marketing.communications.templates.edit.update-success'));

        return redirect()->route('admin.marketing.communications.email_templates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('marketing.templates.delete.before', $id);

            $this->templateRepository->delete($id);

            Event::dispatch('marketing.templates.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.marketing.communications.templates.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.marketing.communications.templates.delete-failed', [
                'name' => 'admin::app.marketing.communications.templates.email-template',
            ]),
        ], 400);
    }
}
