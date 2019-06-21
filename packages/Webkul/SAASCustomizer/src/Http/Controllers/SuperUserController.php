<?php

namespace Webkul\SAASCustomizer\Http\Controllers;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Models\SuperAdmin;
use Company;

/**
 * Super User controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SuperUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:super-admin', ['only' => ['list']]);
    }

    /**
     * To show the login screen
     */
    public function index()
    {
        if (! auth()->guard('super-admin')->check()) {
            return view('saas::companies.auth.login');
        } else {
            return redirect()->route('super.session.index');
        }
    }

    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (! auth()->guard('super-admin')->attempt(request(['email', 'password']))) {
            session()->flash('error', trans('admin::app.users.users.login-error'));

            return redirect()->route('super.session.index');
        }

        session()->flash('success', 'Logged in successfully');

        return redirect()->route('super.companies.index');
    }

    public function list()
    {
        return view('saas::companies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        auth()->guard('super-admin')->logout();

        return redirect()->route('super.session.index');
    }
}