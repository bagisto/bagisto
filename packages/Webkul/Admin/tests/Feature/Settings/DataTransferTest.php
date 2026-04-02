<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\DataTransfer\Models\Import;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    Storage::fake('private');
});

/**
 * Create an import record with sensible defaults.
 */
function createImport(array $attributes = []): Import
{
    return Import::create(array_merge([
        'type' => 'products',
        'state' => 'pending',
        'file_path' => 'imports/test.csv',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
    ], $attributes));
}

// ============================================================================
// Index
// ============================================================================

it('should return the data transfer imports index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.index.title'));
});

it('should deny guest access to the data transfer imports index page', function () {
    get(route('admin.settings.data_transfer.imports.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the data transfer import create page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store an import with a CSV file', function () {
    $file = UploadedFile::fake()->createWithContent('customers.csv', "email,first_name,last_name\njohn@example.com,John,Doe");

    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'customers',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('imports', [
        'type' => 'customers',
        'action' => 'append',
        'state' => 'pending',
    ]);
});

it('should store an import with an XML file', function () {
    $file = UploadedFile::fake()->createWithContent(
        'customers.xml',
        '<?xml version="1.0"?><customers><customer email="test@example.com"/></customers>'
    );

    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'customers',
        'action' => 'delete',
        'validation_strategy' => 'skip-errors',
        'allowed_errors' => 5,
        'field_separator' => ',',
        'file' => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('imports', [
        'type' => 'customers',
        'action' => 'delete',
    ]);
});

it('should accept all supported file formats', function () {
    $this->loginAsAdmin();

    $formats = [
        'csv' => 'text/csv',
        'xml' => 'text/xml',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    foreach ($formats as $extension => $mimeType) {
        postJson(route('admin.settings.data_transfer.imports.store'), [
            'type' => 'products',
            'action' => 'append',
            'validation_strategy' => 'stop-on-errors',
            'allowed_errors' => 10,
            'field_separator' => ',',
            'file' => UploadedFile::fake()->create("test.{$extension}", 100, $mimeType),
        ])
            ->assertRedirect();
    }
});

it('should accept a CSV file with text/plain MIME type', function () {
    $file = UploadedFile::fake()->createWithContent('test.csv', "id,name\n1,Test");

    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 0,
        'field_separator' => ',',
        'file' => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('action')
        ->assertJsonValidationErrorFor('validation_strategy')
        ->assertJsonValidationErrorFor('allowed_errors')
        ->assertJsonValidationErrorFor('field_separator')
        ->assertJsonValidationErrorFor('file');
});

it('should fail validation with an invalid file type on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => UploadedFile::fake()->create('invalid.txt', 100, 'text/plain'),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('file');
});

it('should fail validation with an invalid action on store', function () {
    $file = UploadedFile::fake()->createWithContent('test.csv', 'data');

    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'invalid_action',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => $file,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('action');
});

it('should fail validation with an invalid validation strategy on store', function () {
    $file = UploadedFile::fake()->createWithContent('test.csv', 'data');

    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'invalid_strategy',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => $file,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('validation_strategy');
});

it('should handle the process_in_queue option on store', function () {
    $this->loginAsAdmin();

    // With process_in_queue enabled.
    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'customers',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => UploadedFile::fake()->createWithContent('queue.csv', 'data'),
        'process_in_queue' => 1,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $queued = Import::where('process_in_queue', true)->latest()->first();

    expect($queued)->not->toBeNull();
    expect($queued->process_in_queue)->toBe(true);

    // Without process_in_queue (defaults to false).
    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'delete',
        'validation_strategy' => 'skip-errors',
        'allowed_errors' => 5,
        'field_separator' => ';',
        'file' => UploadedFile::fake()->createWithContent('no_queue.csv', 'data'),
    ])
        ->assertRedirect();

    $notQueued = Import::where('type', 'products')->where('field_separator', ';')->latest()->first();

    expect($notQueued->process_in_queue)->toBe(false);
});

it('should sanitize the filename on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 10,
        'field_separator' => ',',
        'file' => UploadedFile::fake()->createWithContent('../../malicious.csv', 'data'),
    ])
        ->assertRedirect();

    $import = Import::latest()->first();

    expect($import->file_path)->toContain('imports/');
    expect($import->file_path)->not()->toContain('../');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page for an existing import', function () {
    $import = createImport();

    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.edit', $import->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing import with a new file', function () {
    $import = createImport(['file_path' => 'imports/old.csv']);

    $this->loginAsAdmin();

    putJson(route('admin.settings.data_transfer.imports.update', $import->id), [
        'type' => 'products',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 15,
        'field_separator' => ',',
        'file' => UploadedFile::fake()->createWithContent('products.csv', "sku,name\nSKU-001,Product 1"),
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $import->refresh();

    expect($import->allowed_errors)->toBe(15);
    expect($import->state)->toBe('pending');
});

it('should update an existing import without providing a new file', function () {
    $import = createImport(['type' => 'customers', 'file_path' => 'imports/existing.csv']);

    $this->loginAsAdmin();

    putJson(route('admin.settings.data_transfer.imports.update', $import->id), [
        'type' => 'customers',
        'action' => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors' => 20,
        'field_separator' => ',',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $import->refresh();

    expect($import->allowed_errors)->toBe(20);
    expect($import->file_path)->toBe('imports/existing.csv');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an import and its file', function () {
    $import = createImport();

    Storage::disk('private')->put($import->file_path, 'test content');

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.data_transfer.imports.delete', $import->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('admin::app.settings.data-transfer.imports.delete-success')]);

    $this->assertDatabaseMissing('imports', ['id' => $import->id]);
});

// ============================================================================
// Import
// ============================================================================

it('should return the import page with stats', function () {
    $import = createImport(['type' => 'customers', 'processed_rows_count' => 0]);

    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.import', $import->id))
        ->assertOk()
        ->assertSeeText($import->type);
});
