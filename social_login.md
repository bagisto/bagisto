# Adding social login in bagisto.
1. Create credential and add **GOOGLE_CLIENT_ID**, **GOOGLE_CLIENT_SECRECT** and **GOOGLE_REDIRECT** to **.env** files
```
GOOGLE_CLIENT_ID = 696315687697-tc7fg4i370u0bvihersa7a61......apps.googleusercontent.com
GOOGLE_CLIENT_SECRET = crdQ4AIGHrv.......8m9c9
GOOGLE_REDIRECT = http://localhost:8000/sign-in/google/redirect
```
* your these 3 values may be different
2. add credentials in service.php
```
'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT')
    ],
```
3. login button
create a login button and redirect it to ```/sign-in/github```

4. add routes in web.php 
```Route::get('/sign-in/github', 'Auth\LoginController@github');```
```Route::get('/sign-in/github/redirect', 'Auth\LoginController@githubRedirect');```

5. Create contorllers using socialite
```
use Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\User;

//Send the user request to google
public function google(){
        return Socialite::driver('google')->redirect();
}

//get the auth request from the google to authenticate 
public function goo gleRedirect(){
    $user = Socialite::driver('google')->stateless()->user();

    $user = User::firstOrCreate([
        'email' => $user->email
    ], [
        'name' =>  $user->name,
        'password' => Hash::make(Str::random(24))
    ]);
    Auth::login($user, true);
    return redirect('/dashboard'); 
}
```
##Other resources.
https://laravel.com/docs/7.x/socialite
https://www.youtube.com/watch?v=FLsSEV5ulD4

