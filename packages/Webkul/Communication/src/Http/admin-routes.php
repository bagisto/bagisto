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

});