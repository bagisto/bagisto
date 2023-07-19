<?php

namespace Webkul\Admin\Http\Controllers\Marketing;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketing\Repositories\EventRepository;
use Webkul\Admin\DataGrids\EventDataGrid;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EventRepository $eventRepository)
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
            return app(EventDataGrid::class)->toJson();
        }

        return view('admin::marketing.email-marketing.events.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::marketing.email-marketing.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.create.before');

        $event = $this->eventRepository->create(request()->all());

        Event::dispatch('marketing.events.create.after', $event);

        session()->flash('success', trans('admin::app.marketing.events.create-success'));

        return redirect()->route('admin.events.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if ($id == 1) {
            session()->flash('error', trans('admin::app.marketing.events.edit-error'));

            return redirect()->back();
        }

        $event = $this->eventRepository->findOrFail($id);

        return view('admin::marketing.email-marketing.events.edit', compact('event'));
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
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.update.before', $id);

        $event = $this->eventRepository->update(request()->all(), $id);

        Event::dispatch('marketing.events.update.after', $event);

        session()->flash('success', trans('admin::app.marketing.events.update-success'));

        return redirect()->route('admin.events.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->eventRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.events.delete.before', $id);

            $this->eventRepository->delete($id);

            Event::dispatch('marketing.events.delete.after', $id);

            return response()->json(['message' => trans('admin::app.marketing.events.delete-success')]);
        } catch (\Exception $e) {
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Event'])], 500);
    }
}
