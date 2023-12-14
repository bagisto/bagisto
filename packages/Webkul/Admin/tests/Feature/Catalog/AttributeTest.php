<?php 

use function Pest\Laravel\get;

it('it should show attribute page', function () {
    // Act & Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.index'), [
        'Content-Type' => 'text/html',
        'Accept'       => 'text/html',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.index.create-btn'));
});


it('it should returns attributes options', function () {
    // Act & Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.options'), [
        'Content-Type' => 'text/html',
        'Accept'       => 'text/html',
    ])  
        ->dd()
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.index.create-btn'));
});


?>