<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\CountryRepository as Country;

/**
 * Country controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CountryController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CountryRepository object
     *
     * @var array
     */
    protected $country;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CountryRepository $country
     * @return void
     */
    public function __construct(Country $country)
    {
        $this->middleware('admin');

        $this->country = $country;

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
            'code' => 'required|unique:countries,code',
            'name' => 'required'
        ]);

        $this->country->create(request()->all());

        session()->flash('success', 'Country created successfully.');

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