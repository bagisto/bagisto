<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param  Webkul\Core\Repositories\LocaleRepository $locale
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
            'code' => 'required|unique:locales,code',
            'name' => 'required'
        ]);

        $this->locale->create(request()->all());

        session()->flash('success', 'Locale created successfully.');

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
        //
    }
}