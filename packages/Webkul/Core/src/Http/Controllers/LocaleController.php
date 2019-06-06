<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\LocaleRepository as Locale;

/**
 * Locale controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class LocaleController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * LocaleRepository object
     *
     * @var array
     */
    protected $locale;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\LocaleRepository $locale
     * @return void
     */
    public function __construct(Locale $locale)
    {
        $this->locale = $locale;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:locales,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        Event::fire('core.locale.create.before');

        $locale = $this->locale->create(request()->all());

        Event::fire('core.locale.create.after', $locale);

        session()->flash('success', trans('admin::app.settings.locales.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locale = $this->locale->findOrFail($id);

        return view($this->_config['view'], compact('locale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:locales,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        Event::fire('core.locale.update.before', $id);

        $locale = $this->locale->update(request()->all(), $id);

        Event::fire('core.locale.update.after', $locale);

        session()->flash('success', trans('admin::app.settings.locales.update-success'));

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
        $locale = $this->locale->findOrFail($id);

        if ($this->locale->count() == 1) {
            session()->flash('error', trans('admin::app.settings.locales.last-delete-error'));
        } else {
            try {
                Event::fire('core.locale.delete.before', $id);

                $this->locale->delete($id);

                Event::fire('core.locale.delete.after', $id);

                session()->flash('success', trans('admin::app.settings.locales.delete-success'));

                return response()->json(['message' => true], 200);
            } catch(\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Locale']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}