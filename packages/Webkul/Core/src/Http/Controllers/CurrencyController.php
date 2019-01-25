<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\CurrencyRepository as Currency;

/**
 * Currency controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CurrencyController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CurrencyRepository object
     *
     * @var array
     */
    protected $currency;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CurrencyRepository $currency
     * @return void
     */
    public function __construct(Currency $currency)
    {
        $this->currency = $currency;

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
            'code' => 'required|min:3|max:3|unique:currencies,code',
            'name' => 'required'
        ]);

        Event::fire('core.channel.create.before');

        $currency = $this->currency->create(request()->all());

        Event::fire('core.currency.create.after', $currency);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Currency']));

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
        $currency = $this->currency->find($id);

        return view($this->_config['view'], compact('currency'));
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
            'code' => ['required', 'unique:currencies,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        Event::fire('core.currency.update.before', $id);

        $currency = $this->currency->update(request()->all(), $id);

        Event::fire('core.currency.update.after', $currency);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Currency']));

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
        try {
            Event::fire('core.currency.delete.before', $id);

            $result = $this->currency->delete($id);

            Event::fire('core.currency.delete.after', $id);

            if($result)
                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Currency']));
            else
                session()->flash('error', trans('admin::app.response.last-delete-error', ['name' => 'Currency']));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy() {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    Event::fire('core.currency.delete.before', $value);

                    $this->currency->delete($value);

                    Event::fire('core.currency.delete.after', $value);
                } catch(\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'currencies']));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'currencies']));

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}