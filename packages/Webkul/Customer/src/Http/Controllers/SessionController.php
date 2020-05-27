<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Socialite;
use Cookie;

class SessionController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new Repository instance.
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('customer')->except(['show','create','socialLogin','socialLoginCallback']);

        $this->_config = request('_config');
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {

        if (auth()->guard('customer')->check()) {
            return redirect()->route('customer.profile.index');
        } else {

    // Configure ENV variables if Social Login Active
            
            if(core()->getConfigData('social-login.settings.sociallogins-enable-disable.sociallogins_status'))
            {
                if(core()->getConfigData('social-login.settings.sociallogins-facebook.sociallogins_facebook_status'))
                {
                    $env_update = $this->changeEnv([
                        'FACEBOOK_CLIENT_ID'   => core()->getConfigData('social-login.settings.sociallogins-facebook.facebook_appid'),
                        'FACEBOOK_CLIENT_SECRET'   => core()->getConfigData('social-login.settings.sociallogins-facebook.facebook_appsecret'),
                    ]);
                }
                if(core()->getConfigData('social-login.settings.sociallogins-google.sociallogins_google_status'))
                {
                    $env_update = $this->changeEnv([
                        'GOOGLE_CLIENT_ID'   => core()->getConfigData('social-login.settings.sociallogins-google.google_appid'),
                        'GOOGLE_CLIENT_SECRET'   => core()->getConfigData('social-login.settings.sociallogins-google.google_appsecret')
                    ]);        
                }
                
            }

            return view($this->_config['view']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate(request(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }
        
        if (auth()->guard('customer')->user()->status == 0) {
            auth()->guard('customer')->logout();

            session()->flash('warning', trans('shop::app.customer.login-form.not-activated'));

            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->is_verified == 0) {
            session()->flash('info', trans('shop::app.customer.login-form.verify-first'));

            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', request('email'), 1));

            auth()->guard('customer')->logout();

            return redirect()->back();
        }

        //Event passed to prepare cart after login
        Event::dispatch('customer.after.login', request('email'));

        return redirect()->intended(route($this->_config['redirect']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->guard('customer')->logout();

        Event::dispatch('customer.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }

// Social Login Methods
    public function socialLogin($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    protected function changeEnv($data = array())
    {
        if(count($data) > 0){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);
            
            return true;
        } else {
            return false;
        }
    }
}