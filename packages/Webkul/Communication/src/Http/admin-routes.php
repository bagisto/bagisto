<?php

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => config('app.admin_url') . '/communication',
    'namespace' => 'Webkul\Communication\Http\Controllers\Admin'
], function () {

    Route::get('newsletter-templates', 'NewsletterTemplateController@index')->defaults('_config', [
        'view' => 'communication::admin.newsletter-template.index',
    ])->name('communication.newsletter-templates.index');

    Route::get('newsletter-templates/create', 'NewsletterTemplateController@create')->defaults('_config', [
        'view' => 'communication::admin.newsletter-template.create',
    ])->name('communication.newsletter-templates.create');

    Route::post('newsletter-templates/store', 'NewsletterTemplateController@store')->name('communication.newsletter-templates.store');

    Route::get('newsletter-templates/edit/{id}', 'NewsletterTemplateController@edit')->defaults('_config', [
        'view' => 'communication::admin.newsletter-template.edit',
    ])->name('communication.newsletter-templates.edit');

    Route::post('newsletter-templates/update/{id}', 'NewsletterTemplateController@update')->defaults('_config', [
        'redirect' => 'communication.newsletter-templates.index'
    ])->name('communication.newsletter-templates.update');

    Route::delete('newsletter-templates/delete/{id}', 'NewsletterTemplateController@destroy')->name('communication.newsletter-templates.delete');
});