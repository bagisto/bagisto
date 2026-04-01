<?php

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Product\Models\Product;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Helpers
// ============================================================================

/**
 * Build the attribute_groups payload matching the real browser format.
 *
 * The UI sends groups keyed by existing group IDs (or 'group_' prefix for new
 * groups), each containing code, name, position, column, and custom_attributes.
 */
function buildGroupsPayloadFromFamily(AttributeFamily $family): array
{
    $family->loadMissing('attribute_groups.custom_attributes');

    $payload = [];

    foreach ($family->attribute_groups as $group) {
        $attributes = $group->custom_attributes->map(fn ($attr, $index) => [
            'id' => (string) $attr->id,
            'position' => (string) ($index + 1),
        ])->values()->toArray();

        $payload[(string) $group->id] = [
            'code' => $group->code,
            'name' => $group->name,
            'position' => (string) $group->position,
            'column' => (string) $group->column,
            'is_user_defined' => $group->is_user_defined,
            'custom_attributes' => $attributes,
        ];
    }

    return $payload;
}

/**
 * Build a full store/update payload using the default family as template.
 */
function buildDefaultFamilyPayload(string $code, string $name): array
{
    $defaultFamily = AttributeFamily::with('attribute_groups.custom_attributes')
        ->where('code', 'default')
        ->first();

    $groups = buildGroupsPayloadFromFamily($defaultFamily);

    return [
        'code' => $code,
        'name' => $name,
        'attribute_groups' => $groups,
    ];
}

// ============================================================================
// Index
// ============================================================================

it('should return the attribute family index page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.families.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.index.title'))
        ->assertSeeText(trans('admin::app.catalog.families.index.add'));
});

it('should return attribute family listing via datagrid', function () {
    $family = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.families.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', $family->id)
        ->assertJsonPath('records.0.code', $family->code);
});

it('should deny guest access to the attribute family index page', function () {
    get(route('admin.catalog.families.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the attribute family create page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.families.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.create.title'))
        ->assertSeeText(trans('admin::app.catalog.families.create.back-btn'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a new attribute family with all default groups and attributes', function () {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('family_??????????');
    $payload = buildDefaultFamilyPayload($code, 'Test Family');

    postJson(route('admin.catalog.families.store'), $payload)
        ->assertRedirectToRoute('admin.catalog.families.index');

    $family = AttributeFamily::with('attribute_groups.custom_attributes')
        ->where('code', $code)
        ->first();

    expect($family)->not->toBeNull();
    expect($family->name)->toBe('Test Family');
    expect($family->attribute_groups)->toHaveCount(8);

    // Verify attributes were assigned to the General group.
    $general = $family->attribute_groups->where('code', 'general')->first();

    expect($general)->not->toBeNull();
    expect($general->custom_attributes->pluck('code')->toArray())->toContain('sku', 'name');
});

it('should store a family with groups in both columns', function () {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('family_??????????');
    $payload = buildDefaultFamilyPayload($code, 'Two-Column Family');

    postJson(route('admin.catalog.families.store'), $payload)
        ->assertRedirectToRoute('admin.catalog.families.index');

    $family = AttributeFamily::with('attribute_groups')->where('code', $code)->first();
    $columns = $family->attribute_groups->pluck('column')->unique()->sort()->values()->toArray();

    expect($columns)->toBe([1, 2]);
});

it('should store a family with all default groups plus an extra custom group', function () {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('family_??????????');
    $payload = buildDefaultFamilyPayload($code, 'Extended Family');

    // Add a user-defined group on top of the system groups — this is how the
    // UI works when a user clicks "Add Group" before saving.
    $customAttribute = Attribute::factory()->create(['type' => 'text']);

    $payload['attribute_groups']['group_1_0'] = [
        'code' => 'custom_info',
        'name' => 'Custom Info',
        'position' => '99',
        'column' => '2',
        'is_user_defined' => 1,
        'custom_attributes' => [
            ['id' => (string) $customAttribute->id, 'position' => '1'],
        ],
    ];

    postJson(route('admin.catalog.families.store'), $payload)
        ->assertRedirectToRoute('admin.catalog.families.index');

    $family = AttributeFamily::with('attribute_groups.custom_attributes')
        ->where('code', $code)
        ->first();

    // 8 default groups + 1 custom group.
    expect($family->attribute_groups)->toHaveCount(9);

    $customGroup = $family->attribute_groups->where('code', 'custom_info')->first();

    expect($customGroup)->not->toBeNull();
    expect($customGroup->custom_attributes)->toHaveCount(1);
    expect($customGroup->custom_attributes->first()->id)->toBe($customAttribute->id);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name');
});

it('should fail validation when code starts with a number', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'), [
        'code' => '123invalid',
        'name' => 'Invalid Code',
        'attribute_groups' => [
            '1' => ['code' => 'general', 'name' => 'General', 'column' => '1', 'position' => '1'],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when code already exists on store', function () {
    $existing = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'), [
        'code' => $existing->code,
        'name' => 'Duplicate',
        'attribute_groups' => [
            '1' => ['code' => 'general', 'name' => 'General', 'column' => '1', 'position' => '1'],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when attribute group column is invalid', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'), [
        'code' => fake()->unique()->lexify('family_??????????'),
        'name' => 'Invalid Column',
        'attribute_groups' => [
            '1' => ['code' => 'general', 'name' => 'General', 'column' => '5', 'position' => '1'],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('attribute_groups.1.column');
});

it('should fail validation when attribute group name is missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'), [
        'code' => fake()->unique()->lexify('family_??????????'),
        'name' => 'Missing Group Name',
        'attribute_groups' => [
            '1' => ['code' => 'general', 'column' => '1', 'position' => '1'],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('attribute_groups.1.name');
});

it('should dispatch events when storing a family', function () {
    Event::fake();

    $this->loginAsAdmin();

    $payload = buildDefaultFamilyPayload(
        fake()->unique()->lexify('family_??????????'),
        'Event Family',
    );

    postJson(route('admin.catalog.families.store'), $payload)
        ->assertRedirectToRoute('admin.catalog.families.index');

    Event::assertDispatched('catalog.attribute_family.create.before');
    Event::assertDispatched('catalog.attribute_family.create.after');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the attribute family edit page with all groups', function () {
    $this->loginAsAdmin();

    $defaultFamily = AttributeFamily::where('code', 'default')->first();

    get(route('admin.catalog.families.edit', $defaultFamily->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.edit.title'))
        ->assertSeeText('General')
        ->assertSeeText('Price')
        ->assertSeeText('Shipping')
        ->assertSeeText('Description')
        ->assertSeeText('Meta Description')
        ->assertSeeText('Settings');
});

it('should return 404 for a non-existent family edit page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.families.edit', 99999))
        ->assertNotFound();
});

// ============================================================================
// Update
// ============================================================================

it('should update a family name while preserving all groups and attributes', function () {
    $this->loginAsAdmin();

    $defaultFamily = AttributeFamily::where('code', 'default')->first();
    $originalGroupCount = $defaultFamily->attribute_groups()->count();

    $payload = buildGroupsPayloadFromFamily($defaultFamily);

    putJson(route('admin.catalog.families.update', $defaultFamily->id), [
        'code' => $defaultFamily->code,
        'name' => 'Default Updated',
        'attribute_groups' => $payload,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index');

    $this->assertDatabaseHas('attribute_families', [
        'id' => $defaultFamily->id,
        'name' => 'Default Updated',
    ]);

    // All original groups should still exist.
    expect($defaultFamily->fresh()->attribute_groups()->count())->toBe($originalGroupCount);
});

it('should add a new group to an existing family during update', function () {
    $this->loginAsAdmin();

    $defaultFamily = AttributeFamily::where('code', 'default')->first();
    $originalGroupCount = $defaultFamily->attribute_groups()->count();

    $payload = buildGroupsPayloadFromFamily($defaultFamily);

    // New group uses 'group_' prefix key — this is how the UI adds groups.
    $payload['group_1_new'] = [
        'code' => 'custom_section',
        'name' => 'Custom Section',
        'column' => '2',
        'position' => '99',
        'is_user_defined' => 1,
    ];

    putJson(route('admin.catalog.families.update', $defaultFamily->id), [
        'code' => $defaultFamily->code,
        'name' => $defaultFamily->name,
        'attribute_groups' => $payload,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index');

    expect($defaultFamily->fresh()->attribute_groups()->count())->toBe($originalGroupCount + 1);

    $this->assertDatabaseHas('attribute_groups', [
        'attribute_family_id' => $defaultFamily->id,
        'code' => 'custom_section',
    ]);
});

it('should remove a user-defined group when omitted from update payload', function () {
    $this->loginAsAdmin();

    // Create a family with all default groups + two custom groups.
    $code = fake()->unique()->lexify('family_??????????');
    $payload = buildDefaultFamilyPayload($code, 'Removable Groups');

    $payload['attribute_groups']['group_1_0'] = [
        'code' => 'keep_me', 'name' => 'Keep Me', 'column' => '1', 'position' => '90',
        'is_user_defined' => 1,
    ];
    $payload['attribute_groups']['group_2_0'] = [
        'code' => 'remove_me', 'name' => 'Remove Me', 'column' => '2', 'position' => '91',
        'is_user_defined' => 1,
    ];

    postJson(route('admin.catalog.families.store'), $payload)
        ->assertRedirectToRoute('admin.catalog.families.index');

    $family = AttributeFamily::with('attribute_groups')->where('code', $code)->first();

    // 8 default + 2 custom = 10 groups.
    expect($family->attribute_groups)->toHaveCount(10);

    $keepGroup = $family->attribute_groups->where('code', 'keep_me')->first();
    $removeGroup = $family->attribute_groups->where('code', 'remove_me')->first();

    // Build update payload from current state, then remove the 'remove_me' group.
    $updatePayload = buildGroupsPayloadFromFamily($family);
    unset($updatePayload[(string) $removeGroup->id]);

    putJson(route('admin.catalog.families.update', $family->id), [
        'code' => $family->code,
        'name' => $family->name,
        'attribute_groups' => $updatePayload,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index');

    // 10 - 1 removed = 9 groups.
    expect($family->fresh()->attribute_groups()->count())->toBe(9);

    $this->assertDatabaseMissing('attribute_groups', ['id' => $removeGroup->id]);
    $this->assertDatabaseHas('attribute_groups', ['id' => $keepGroup->id]);
});

it('should fail validation when required fields are missing on update', function () {
    $family = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.families.update', $family->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name');
});

it('should allow updating a family with its own code', function () {
    $this->loginAsAdmin();

    $defaultFamily = AttributeFamily::where('code', 'default')->first();
    $payload = buildGroupsPayloadFromFamily($defaultFamily);

    putJson(route('admin.catalog.families.update', $defaultFamily->id), [
        'code' => $defaultFamily->code,
        'name' => $defaultFamily->name,
        'attribute_groups' => $payload,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index');
});

it('should fail validation when code conflicts with another family on update', function () {
    $familyA = AttributeFamily::factory()->create();
    $familyB = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.families.update', $familyB->id), [
        'code' => $familyA->code,
        'name' => 'Conflict',
        'attribute_groups' => [
            'group_1' => [
                'code' => 'general', 'name' => 'General', 'column' => '1', 'position' => '1',
                'is_user_defined' => 1,
            ],
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should dispatch events when updating a family', function () {
    Event::fake();

    $this->loginAsAdmin();

    $defaultFamily = AttributeFamily::where('code', 'default')->first();
    $payload = buildGroupsPayloadFromFamily($defaultFamily);

    putJson(route('admin.catalog.families.update', $defaultFamily->id), [
        'code' => $defaultFamily->code,
        'name' => 'Updated',
        'attribute_groups' => $payload,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index');

    Event::assertDispatched('catalog.attribute_family.update.before');
    Event::assertDispatched('catalog.attribute_family.update.after');
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete an attribute family', function () {
    $family = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', $family->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.delete-success'));

    $this->assertDatabaseMissing('attribute_families', [
        'id' => $family->id,
    ]);
});

it('should not delete the last remaining attribute family', function () {
    $this->loginAsAdmin();

    // The seeded database has exactly one attribute family (default).
    deleteJson(route('admin.catalog.families.delete', 1))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.families.last-delete-error'));

    $this->assertDatabaseHas('attribute_families', ['id' => 1]);
});

it('should not delete a family that has associated products', function () {
    $family = AttributeFamily::factory()->create();

    Product::factory()->create([
        'attribute_family_id' => $family->id,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', $family->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.families.attribute-product-error'));

    $this->assertDatabaseHas('attribute_families', [
        'id' => $family->id,
    ]);
});

it('should return 404 when deleting a non-existent family', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', 99999))
        ->assertNotFound();
});

it('should dispatch events when deleting a family', function () {
    Event::fake();

    $family = AttributeFamily::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', $family->id))
        ->assertOk();

    Event::assertDispatched('catalog.attribute_family.delete.before');
    Event::assertDispatched('catalog.attribute_family.delete.after');
});
