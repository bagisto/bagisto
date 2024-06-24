<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Marketing\Communications\EventDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketing\Repositories\EventRepository;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EventRepository $eventRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(EventDataGrid::class)->process();
        }

        return view('admin::marketing.communications.events.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->validate(request(), [
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.create.before');

        $event = $this->eventRepository->create(request()->only([
            'name',
            'description',
            'date',
        ]));

        Event::dispatch('marketing.events.create.after', $event);

        return response()->json([
            'message' => trans('admin::app.marketing.communications.events.index.create.success'),
        ], 200);
    }

    /**
     * Event Details
     */
    public function edit(int $id): JsonResponse
    {
        if ($id == 1) {
            return new JsonResponse([
                'message' => trans('admin::app.marketing.communications.events.edit-error'),
            ]);
        }

        $event = $this->eventRepository->findOrFail($id);

        return new JsonResponse($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $id = request()->id;

        $this->validate(request(), [
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.update.before', $id);

        $event = $this->eventRepository->update(request()->only([
            'name',
            'description',
            'date',
        ]), $id);

        Event::dispatch('marketing.events.update.after', $event);

        return response()->json([
            'message' => trans('admin::app.marketing.communications.events.index.edit.success'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $this->eventRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.events.delete.before', $id);

            $this->eventRepository->delete($id);

            Event::dispatch('marketing.events.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.marketing.communications.events.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.marketing.communications.events.delete-failed', ['name'  =>  'admin::app.marketing.communications.events.index.event']),
        ], 500);
    }
}
