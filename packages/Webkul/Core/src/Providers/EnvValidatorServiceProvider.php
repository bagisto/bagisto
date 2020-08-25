<?php

namespace Webkul\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class EnvValidatorServiceProvider extends ServiceProvider
{
    /*
     * Set environment variable rules.
     */
    protected $rules = [
        'DB_PREFIX' => 'not_regex:/[^A-Za-z0-9]/'
    ];

    /*
     * Set environment variable error messages.
     */
    protected $messages = [
        'not_regex' => 'DB_PREFIX ENV is not valid.'
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $validator = Validator::make($_ENV, $this->rules, $this->messages);

        if ($validator->fails()) {
            abort(500, $validator->errors()->first());
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
