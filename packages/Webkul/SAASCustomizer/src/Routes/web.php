<?php

Route::group(['middleware' => 'web'], function () {
    Route::prefix('company')->group(function() {
        //create company
        Route::get('register', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@create')->defaults('_config', [
            'view' => 'saas::companies.auth.register'
        ])->name('company.create.index');

        Route::post('validate/step-one', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@validateStepOne')->name('company.validate.step-one');

        Route::post('validate/step-two', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@validateStepOne')->name('company.validate.step-two');

        Route::post('validate/step-three', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@validateStepOne')->name('company.validate.step-three');

        Route::post('register', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@store')->name('company.create.store');

        Route::get('/seed-data', 'Webkul\SAASCustomizer\Http\Controllers\PurgeController@seedDatabase')->name('company.create.data');
    });

    Route::prefix('super')->group(function() {
        Route::get('login', 'Webkul\SAASCustomizer\Http\Controllers\SuperUserController@index')->name('super.session.index');

        Route::post('login', 'Webkul\SAASCustomizer\Http\Controllers\SuperUserController@store')->defaults('_config', [
            'redirect' => 'super.companies.index'
        ])->name('super.session.create');

        Route::get('logout', 'Webkul\SAASCustomizer\Http\Controllers\SuperUserController@destroy')->name('super.session.destroy');

        // GET Route leading to the listing of the companies grid
        Route::get('companies', 'Webkul\SAASCustomizer\Http\Controllers\SuperUserController@list')->name('super.companies.index');

        Route::get('companies/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@edit')->name('super.companies.edit');

        Route::post('companies/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@update')->name('super.companies.update');

        // To show the stats of the registered companies
        Route::get('companies/view/{id}', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@showCompanyStats')->name('super.companies.show-stats');

        // To show the stats of the registered companies
        Route::get('companies/status/{id}', 'Webkul\SAASCustomizer\Http\Controllers\CompanyController@changeStatus')->name('super.companies.change-status');
    });
});