<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{

    public function switchLanguage($locale){
        
        dd($locale);

        $availableLocales = ['en', 'fr', 'hi']; 
                if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale); // save in session
            App::setLocale($locale);         // set for current request
        }

        return redirect()->back(); // go back to previous page
    }

}