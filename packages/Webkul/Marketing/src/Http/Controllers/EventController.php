<?php

namespace Webkul\Marketing\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Marketing\Repositories\EventRepository;

class EventController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * EventRepository object
     *
     * @var \Webkul\Marketing\Repositories\EventRepository
     */
    protected $eventRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketing\Repositories\EventRepository  $eventRepository
     * @return void
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;

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
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.create.before');

        $locale = $this->eventRepository->create(request()->all());

        Event::dispatch('marketing.events.create.after', $locale);

        session()->flash('success', trans('admin::app.marketing.events.create-success'));

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
        if ($id == 1) {
            session()->flash('error', trans('admin::app.marketing.events.edit-error'));

            return redirect()->back();
        } else {
            $event = $this->eventRepository->findOrFail($id);

            return view($this->_config['view'], compact('event'));
        }
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

        $locale = $this->eventRepository->update(request()->all(), $id);

        Event::dispatch('marketing.events.update.after', $locale);

        session()->flash('success', trans('admin::app.marketing.events.update-success'));

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
        $locale = $this->eventRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.events.delete.before', $id);

            $this->eventRepository->delete($id);

            Event::dispatch('marketing.events.delete.after', $id);

            session()->flash('success', trans('admin::app.marketing.events.delete-success'));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Event']));
        }

        return response()->json(['message' => false], 400);
    }
}
