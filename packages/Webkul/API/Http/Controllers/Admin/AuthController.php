<?php

namespace Webkul\API\Http\Controllers\Admin;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\User\Models\Admin;

/**
 * Session controller for the APIs of user admins
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->guard('admin')->attempt(request(['email', 'password']))) {
            return response()->json(false, 200);
        }

        if(auth()->guard('admin')->check()) {
            $admin = auth()->guard('admin')->user();

            $token = $admin->createToken('admin-token')->accessToken;

            return response()->json($token, 200);
        } else {
            return response()->json(false, 200);
        }
    }
}