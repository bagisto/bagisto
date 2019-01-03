<?php

namespace Webkul\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\User\Http\Controllers\Controller;
use Hash;

/**
 * Admin user account controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AccountController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->guard('admin')->user();

        return view($this->_config['view'], compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $user = auth()->guard('admin')->user();

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'email|unique:admins,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'current_password' => 'required|min:6'
        ]);

        $data = request()->input();

        if(!Hash::check($data['current_password'], auth()->guard('admin')->user()->password)) {
            session()->flash('warning', 'Current password does not match.');

            return redirect()->back();
        }

        if(!$data['password'])
            unset($data['password']);
        else
            $data['password'] = bcrypt($data['password']);

        $user->update($data);

        session()->flash('success', 'Account changes saved successfully.');

        return back();
    }
}