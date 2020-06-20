<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function google(){
        //Send the user request to google
        return Socialite::driver('google')->redirect();
    }
    
    public function googleRedirect(){
        //get the auth request from the google to authenticate user
        $user = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $user->email], 
            ['name' =>  $user->name],
            ['password' => Hash::make(Str::random(24))]
        );
        Auth::login($user, true);
        return redirect('/dashboard');     
}
