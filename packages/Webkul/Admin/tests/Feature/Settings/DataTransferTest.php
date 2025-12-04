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

it('should return the data transfer imports index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.index.title'));
});

it('should return the create page of data transfer import', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.create.title'));
});

it('should fail validation when required fields are not provided when storing import', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('action')
        ->assertJsonValidationErrorFor('validation_strategy')
        ->assertJsonValidationErrorFor('allowed_errors')
        ->assertJsonValidationErrorFor('field_separator')
        ->assertJsonValidationErrorFor('file')
        ->assertUnprocessable();
});

it('should fail validation with invalid file type when storing import', function () {
    // Arrange
    $file = UploadedFile::fake()->create('invalid.txt', 100, 'text/plain');

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertJsonValidationErrorFor('file')
        ->assertUnprocessable();
});

it('should successfully store import with CSV file', function () {
    // Arrange
    Storage::fake('private');

    $csvContent = "email,first_name,last_name\njohn@example.com,John,Doe";
    $file = UploadedFile::fake()->createWithContent('customers.csv', $csvContent);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'customers',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('imports', [
        'type'   => 'customers',
        'action' => 'append',
        'state'  => 'pending',
    ]);
});

it('should accept CSV file with text/plain MIME type (small files)', function () {
    // Arrange
    Storage::fake('private');

    $csvContent = "id,name\n1,Test";
    $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 0,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');
});

it('should successfully store import with XML file', function () {
    // Arrange
    Storage::fake('private');

    $xmlContent = '<?xml version="1.0"?><customers><customer email="test@example.com"/></customers>';
    $file = UploadedFile::fake()->createWithContent('customers.xml', $xmlContent);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'customers',
        'action'              => 'delete',
        'validation_strategy' => 'skip-errors',
        'allowed_errors'      => 5,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('imports', [
        'type'   => 'customers',
        'action' => 'delete',
    ]);
});

it('should return the edit page for an existing import', function () {
    // Arrange
    $import = Import::create([
        'type'                => 'products',
        'state'               => 'pending',
        'file_path'           => 'imports/test.csv',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.edit', $import->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.data-transfer.imports.edit.title'));
});

it('should successfully update an existing import', function () {
    // Arrange
    Storage::fake('private');

    $import = Import::create([
        'type'                => 'products',
        'state'               => 'pending',
        'file_path'           => 'imports/old.csv',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
    ]);

    $csvContent = "sku,name\nSKU-001,Product 1";

    $newFile = UploadedFile::fake()->createWithContent('products.csv', $csvContent);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.data_transfer.imports.update', $import->id), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 15,
        'field_separator'     => ',',
        'file'                => $newFile,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $import->refresh();

    expect($import->allowed_errors)->toBe(15);
    expect($import->state)->toBe('pending');
});

it('should allow updating import without providing a new file', function () {
    // Arrange
    Storage::fake('private');

    $import = Import::create([
        'type'                => 'customers',
        'state'               => 'pending',
        'file_path'           => 'imports/existing.csv',
        'allowed_errors'      => 10,
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'field_separator'     => ',',
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.data_transfer.imports.update', $import->id), [
        'type'                => 'customers',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 20,
        'field_separator'     => ',',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $import->refresh();

    expect($import->allowed_errors)->toBe(20);
    expect($import->file_path)->toBe('imports/existing.csv');
});

it('should successfully delete an import', function () {
    // Arrange
    Storage::fake('private');

    $import = Import::create([
        'type'                => 'products',
        'file_path'           => 'imports/test.csv',
        'state'               => 'pending',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
    ]);

    Storage::disk('private')->put($import->file_path, 'test content');

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.data_transfer.imports.delete', $import->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('admin::app.settings.data-transfer.imports.delete-success')]);

    $this->assertDatabaseMissing('imports', [
        'id' => $import->id,
    ]);
});

it('should return import page with stats', function () {
    // Arrange
    $import = Import::create([
        'type'                 => 'customers',
        'state'                => 'pending',
        'file_path'            => 'imports/customers.csv',
        'processed_rows_count' => 0,
        'action'               => 'append',
        'validation_strategy'  => 'stop-on-errors',
        'allowed_errors'       => 10,
        'field_separator'      => ',',
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.data_transfer.imports.import', $import->id))
        ->assertOk()
        ->assertSeeText($import->type);
});

it('should validate action field with correct syntax', function () {
    // Arrange
    Storage::fake('private');

    $file = UploadedFile::fake()->createWithContent('test.csv', 'data');

    // Act and Assert
    $this->loginAsAdmin();

    // Valid action values should pass
    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertRedirect();

    // Invalid action value should fail
    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'invalid_action',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertJsonValidationErrorFor('action')
        ->assertUnprocessable();
});

it('should validate validation_strategy field with correct syntax', function () {
    // Arrange
    Storage::fake('private');

    $file = UploadedFile::fake()->createWithContent('test.csv', 'data');

    // Act and Assert
    $this->loginAsAdmin();

    // Valid validation_strategy values should pass
    foreach (['stop-on-errors', 'skip-errors'] as $strategy) {
        postJson(route('admin.settings.data_transfer.imports.store'), [
            'type'                => 'products',
            'action'              => 'append',
            'validation_strategy' => $strategy,
            'allowed_errors'      => 10,
            'field_separator'     => ',',
            'file'                => $file,
        ])
            ->assertRedirect();
    }

    // Invalid validation_strategy value should fail
    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'invalid_strategy',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertJsonValidationErrorFor('validation_strategy')
        ->assertUnprocessable();
});

it('should accept all supported file formats', function () {
    // Arrange
    Storage::fake('private');

    $this->loginAsAdmin();

    $formats = [
        'csv'  => 'text/csv',
        'xml'  => 'text/xml',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    foreach ($formats as $extension => $mimeType) {
        $file = UploadedFile::fake()->create("test.{$extension}", 100, $mimeType);

        postJson(route('admin.settings.data_transfer.imports.store'), [
            'type'                => 'products',
            'action'              => 'append',
            'validation_strategy' => 'stop-on-errors',
            'allowed_errors'      => 10,
            'field_separator'     => ',',
            'file'                => $file,
        ])
            ->assertRedirect();
    }
});

it('should handle process_in_queue option correctly', function () {
    // Arrange
    Storage::fake('private');

    $this->loginAsAdmin();

    // Test with process_in_queue enabled
    $file = UploadedFile::fake()->createWithContent('queue_test.csv', 'test data content');

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'customers',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
        'process_in_queue'    => 1,
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    // Verify the import with process_in_queue = 1 exists
    $importWithQueue = Import::where('process_in_queue', 1)->latest()->first();

    expect($importWithQueue)->not->toBeNull();

    expect($importWithQueue->process_in_queue)->toBe(1);

    expect($importWithQueue->type)->toBe('customers');

    // Test without process_in_queue (should default to 0)
    $file2 = UploadedFile::fake()->createWithContent('no_queue_test.csv', 'different test data');

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'delete',
        'validation_strategy' => 'skip-errors',
        'allowed_errors'      => 5,
        'field_separator'     => ';',
        'file'                => $file2,
    ])
        ->assertRedirect();

    // Verify the import without process_in_queue defaults to 0
    $importWithoutQueue = Import::where('type', 'products')
        ->where('action', 'delete')
        ->where('field_separator', ';')
        ->latest()
        ->first();

    expect($importWithoutQueue)->not->toBeNull();

    expect($importWithoutQueue->process_in_queue)->toBe(0);
});

it('should sanitize filename when storing import', function () {
    // Arrange
    Storage::fake('private');

    $file = UploadedFile::fake()->createWithContent('../../malicious.csv', 'data');

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.data_transfer.imports.store'), [
        'type'                => 'products',
        'action'              => 'append',
        'validation_strategy' => 'stop-on-errors',
        'allowed_errors'      => 10,
        'field_separator'     => ',',
        'file'                => $file,
    ])
        ->assertRedirect();

    $import = Import::latest()->first();

    // Verify the filename is sanitized (contains hash and unique ID)
    expect($import->file_path)->toContain('imports/');

    expect($import->file_path)->not()->toContain('../');
});
