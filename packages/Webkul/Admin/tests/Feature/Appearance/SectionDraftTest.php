<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Models\Section;
use Webkul\Theme\Repositories\SectionRepository;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

function makeSection(array $attributes = []): Section
{
    $channel = core()->getCurrentChannel();

    $section = Section::factory()->create(array_merge([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => Section::STATIC_CONTENT,
        'status' => 1,
    ], $attributes));

    /**
     * The factory does not seed a translation, and a section with no options is not a
     * state the editor can reach.
     */
    $section->translateOrNew(app()->getLocale())->options = ['html' => '<p>published</p>'];

    $section->save();

    return $section->refresh();
}

/**
 * The theme a section belongs to, which is what publishing and discarding are addressed to.
 */
function themeCode(?Section $section = null): string
{
    return $section?->theme_code ?: (core()->getCurrentChannel()->theme ?: 'default');
}

/**
 * Publish every pending edit of a section's theme.
 *
 * Publishing is a whole-theme action, so a test that staged one section's edit asks for it by
 * naming the theme. There is deliberately no way to publish a single section: a reorder stages a
 * new position on several of them at once, and releasing only one leaves the list half sorted.
 */
function publishDrafts(?Section $section = null): TestResponse
{
    return postJson(route('admin.appearance.sections.publish', themeCode($section)));
}

/**
 * Throw away every pending edit of a section's theme.
 */
function discardDrafts(?Section $section = null): TestResponse
{
    return postJson(route('admin.appearance.sections.discard', themeCode($section)));
}

it('should hold an edit as a draft without touching what the storefront renders', function () {
    $section = makeSection();

    $published = $section->translate(app()->getLocale())->options;

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>draft copy</p>'],
    ])
        ->assertOk()
        ->assertJsonPath('has_draft', true);

    $translation = $section->fresh()->translate(app()->getLocale());

    expect($translation->draft_options)->toBe(['html' => '<p>draft copy</p>']);

    expect($translation->options)->toBe($published);
});

it('should promote a draft to the published options', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>ready</p>'],
    ])->assertOk();

    publishDrafts($section)
        ->assertOk()
        ->assertJsonPath('published', 1);

    $translation = $section->fresh()->translate(app()->getLocale());

    expect($translation->options)->toBe(['html' => '<p>ready</p>']);

    expect($translation->draft_options)->toBeNull();
});

it('should throw a draft away on discard, leaving the published options alone', function () {
    $section = makeSection();

    $published = $section->translate(app()->getLocale())->options;

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>regret</p>'],
    ])->assertOk();

    discardDrafts($section)
        ->assertOk()
        ->assertJsonPath('discarded', 1);

    $translation = $section->fresh()->translate(app()->getLocale());

    expect($translation->draft_options)->toBeNull();

    expect($translation->options)->toBe($published);
});

it('should publish every locale of a section, not only the one being edited', function () {
    $section = makeSection();

    $section->translateOrNew('en')->draft_options = ['html' => 'en draft'];

    $section->translateOrNew('fr')->draft_options = ['html' => 'fr draft'];

    $section->save();

    app(SectionRepository::class)->publishDraft($section->id);

    $section = $section->fresh();

    expect($section->translate('en')->options)->toBe(['html' => 'en draft']);

    expect($section->translate('fr')->options)->toBe(['html' => 'fr draft']);

    expect($section->translate('fr')->draft_options)->toBeNull();
});

it('should resolve a section to its draft for the preview', function () {
    $channel = core()->getCurrentChannel();

    $section = makeSection();

    $section->translateOrNew(app()->getLocale())->draft_options = ['html' => '<p>only in preview</p>'];

    $section->save();

    $preview = app(SectionRepository::class)->getDraftedForPreview(
        $channel->id,
        $section->theme_code,
        app()->getLocale()
    );

    $previewed = $preview->firstWhere('id', $section->id);

    expect($previewed->translate(app()->getLocale())->options)->toBe(['html' => '<p>only in preview</p>']);
});

it('should keep the preview off limits to guests', function () {
    get(route('shop.appearance.preview'))->assertForbidden();
});

it('should keep the preview off limits to an admin who cannot edit sections', function () {
    $role = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => ['sales', 'sales.orders'],
    ]);

    $this->loginAsAdmin(Admin::factory()->create(['role_id' => $role->id]));

    get(route('shop.appearance.preview'))->assertForbidden();
});

it('should open the preview to an admin who may edit sections', function () {
    makeSection();

    $role = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => ['appearance', 'appearance.sections'],
    ]);

    $this->loginAsAdmin(Admin::factory()->create(['role_id' => $role->id]));

    get(route('shop.appearance.preview'))->assertOk();
});

it('should render the preview for a signed in admin', function () {
    makeSection();

    $this->loginAsAdmin();

    get(route('shop.appearance.preview'))->assertOk();
});

it('should copy a section including its translated options', function () {
    $section = makeSection(['name' => 'Hero', 'status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.duplicate', $section->id))->assertOk();

    $copy = Section::query()->where('id', '!=', $section->id)->latest('id')->first();

    expect($copy->name)->toContain('Hero');

    expect($copy->translate(app()->getLocale())->options)
        ->toBe($section->translate(app()->getLocale())->options);
});

it('should renumber sections when the list is reordered', function () {
    $first = makeSection(['sort_order' => 1]);

    $second = makeSection(['sort_order' => 2]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.reorder'), [
        'sections' => [$second->id, $first->id],
    ])->assertOk();

    expect($second->fresh()->draft_sort_order)->toBe(1);

    expect($first->fresh()->draft_sort_order)->toBe(2);

    publishDrafts($second)->assertOk();

    expect($second->fresh()->sort_order)->toBe(1);

    expect($first->fresh()->sort_order)->toBe(2);
});

it('should reject a draft with no options', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [])
        ->assertUnprocessable();
});

it('should render the split editor when scoped to a theme', function () {
    $section = makeSection(['name' => 'Hero Banner']);

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $section->theme_code]))
        ->assertOk()
        ->assertSee('v-section-editor', false)
        ->assertSee('Hero Banner')
        ->assertSee(route('shop.appearance.preview'), false);
});

it('should still render the editor when no theme is asked for', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => core()->getCurrentChannel()->theme]))
        ->assertOk()
        ->assertSee('v-section-editor', false);
});

it('should flag a section that has unpublished edits in the editor list', function () {
    $section = makeSection(['name' => 'Pending Hero']);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>pending</p>'],
    ])->assertOk();

    get(route('admin.appearance.sections.index', ['code' => $section->theme_code]))
        ->assertOk()
        ->assertSee('"has_draft":true', false);
});

it('should hand the drawer a schema and the published options', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    $response = getJson(route('admin.appearance.sections.fields', $section->id))->assertOk();

    expect($response->json('schema'))->not->toBeEmpty();

    expect($response->json('options'))->toBe(['html' => '<p>published</p>']);
});

it('should hand the drawer the draft once one exists', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>in progress</p>'],
    ])->assertOk();

    getJson(route('admin.appearance.sections.fields', $section->id))
        ->assertOk()
        ->assertJsonPath('options.html', '<p>in progress</p>');
});

it('should store an uploaded image and return its path', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('slide.jpg', 40, 40),
    ])->assertOk();

    $path = $response->json('path');

    expect($path)->toStartWith('storage/themes/'.$section->theme_code.'/sections/'.$section->id.'/')
        ->and($path)->toEndWith('.webp')
        ->and($response->json('type'))->toBe('image');

    Storage::assertExists(str_replace('storage/', '', $path));
});

it('should store an uploaded video as it was given rather than as an image', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->create('clip.mp4', 128, 'video/mp4'),
    ])->assertOk();

    $path = $response->json('path');

    expect($path)->toEndWith('.mp4')
        ->and($response->json('type'))->toBe('video');

    Storage::assertExists(str_replace('storage/', '', $path));
});

it('should refuse a file that is neither an image nor a video', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->create('payload.php', 8, 'text/x-php'),
    ])->assertJsonValidationErrorFor('file');
});

it('should reject a media upload that is not an image', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.media', $section->id), [
        'image' => UploadedFile::fake()->create('notes.txt', 4, 'text/plain'),
    ])->assertUnprocessable();
});

it('should let the preview be framed by the admin', function () {
    $this->loginAsAdmin();

    $response = get(route('shop.appearance.preview'))->assertOk();

    expect($response->headers->get('X-Frame-Options'))->toBeNull();

    expect($response->headers->get('Content-Security-Policy'))->toContain("frame-ancestors 'self'");
});

it('should keep every other page unframable', function () {
    $this->loginAsAdmin();

    foreach ([route('admin.dashboard.index'), route('shop.home.index')] as $url) {
        expect(get($url)->headers->get('X-Frame-Options'))->toBe('DENY');
    }
});

it('should not let a visitor turn framing on from outside the application', function () {
    $vectors = [
        ['GET', '/?framable=1', []],
        ['GET', '/?framable=true', []],
        ['GET', '/?attributes[framable]=1', []],
    ];

    foreach ($vectors as [$method, $uri, $headers]) {
        $response = $this->call($method, $uri, [], [], [], $headers);

        expect($response->headers->get('X-Frame-Options'))
            ->toBe('DENY', $uri.' made the storefront framable');
    }

    foreach (['X-Framable' => '1', 'framable' => '1'] as $header => $value) {
        expect($this->get('/', [$header => $value])->headers->get('X-Frame-Options'))
            ->toBe('DENY', $header.' made the storefront framable');
    }
});

it('should only mark the preview framable, never a sibling storefront page', function () {
    $this->loginAsAdmin();

    get(route('shop.appearance.preview'))->assertOk();

    expect(get(route('shop.home.index'))->headers->get('X-Frame-Options'))->toBe('DENY');
});

it('should stop the preview navigating itself onto a page that cannot be framed', function () {
    $this->loginAsAdmin();

    expect(get(route('shop.home.index'))->headers->get('X-Frame-Options'))->toBe('DENY');

    get(route('shop.appearance.preview'))
        ->assertOk()
        ->assertSee("navigation?.addEventListener('navigate'", false)
        ->assertSee("event.target.closest('a')", false)
        ->assertSee("addEventListener('submit'", false);
});

it('should mark each section in the preview exactly once', function () {
    $this->loginAsAdmin();

    $html = get(route('shop.appearance.preview'))->assertOk()->getContent();

    preg_match_all('/data-section-id="(\d+)"/', $html, $matches);

    $ids = $matches[1];

    expect($ids)->not->toBeEmpty()
        ->and(array_diff_assoc($ids, array_unique($ids)))->toBeEmpty();
});

it('should preview a channel with its own sections, not another channel ones', function () {
    $current = core()->getCurrentChannel();

    $other = Channel::factory()->create(['theme' => $current->theme]);

    Section::factory()->create([
        'type' => 'footer_links',
        'status' => 1,
        'channel_id' => $current->id,
        'theme_code' => $current->theme,
    ]);

    $this->loginAsAdmin();

    $marksIn = function ($channelId) {
        $html = get(route('shop.appearance.preview', ['channel' => $channelId]))
            ->assertOk()
            ->getContent();

        preg_match_all('/data-section-id="(\d+)"/', $html, $matches);

        return array_unique($matches[1]);
    };

    expect($marksIn($other->id))->toBeEmpty()
        ->and($marksIn($current->id))->not->toBeEmpty();
});

it('should render every services section, so a duplicate of one shows up too', function () {
    $channel = core()->getCurrentChannel();

    $names = ['Promises One', 'Promises Two'];

    foreach ($names as $name) {
        $section = Section::factory()->create([
            'type' => 'services_content',
            'name' => $name,
            'status' => 1,
            'channel_id' => $channel->id,
            'theme_code' => $channel->theme,
        ]);

        $section->translateOrNew(app()->getLocale())->options = [
            'services' => [[
                'service_icon' => 'icon-cart',
                'title' => $name.' Title',
                'description' => 'Description',
            ]],
        ];

        $section->save();
    }

    $this->loginAsAdmin();

    $html = get(route('shop.appearance.preview'))->assertOk()->getContent();

    foreach ($names as $name) {
        expect($html)->toContain($name.' Title');
    }
});

it('should keep a pinned footer at the end whatever order is sent', function () {
    $channel = core()->getCurrentChannel();

    $footer = Section::factory()->create([
        'type' => 'footer_links',
        'status' => 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    $other = Section::factory()->create([
        'type' => 'product_carousel',
        'status' => 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.reorder'), [
        'sections' => [$footer->id, $other->id],
    ])->assertOk();

    expect($footer->refresh()->draft_sort_order)->toBeGreaterThan($other->refresh()->draft_sort_order);
});

it('should file an upload under the theme the section belongs to', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    $response = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('slide.jpg', 40, 40),
    ])->assertOk();

    expect($response->json('path'))
        ->toStartWith('storage/themes/'.$section->theme_code.'/sections/'.$section->id.'/');
});

it('should clear a section media directory when the section is deleted', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    $path = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('slide.jpg', 40, 40),
    ])->json('path');

    Storage::assertExists(str_replace('storage/', '', $path));

    deleteJson(route('admin.appearance.sections.delete', $section->id))->assertOk();

    Storage::assertMissing(str_replace('storage/', '', $path));
});

it('should announce every write it makes, before and after', function (string $route, string $event, array $payload) {
    $section = makeSection();

    Event::fake();

    $this->loginAsAdmin();

    postJson(route($route, $section->id), $payload)->assertOk();

    Event::assertDispatched($event.'.before');

    Event::assertDispatched($event.'.after');
})->with([
    'draft saved' => ['admin.appearance.sections.draft', 'section.draft.save', ['options' => ['html' => '<p>d</p>']]],
    'status changed' => ['admin.appearance.sections.status', 'section.draft.save', ['status' => false]],
    'section duplicated' => ['admin.appearance.sections.duplicate', 'section.create', []],
]);

it('should announce a publish for each section it releases, before and after', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>ready</p>'],
    ])->assertOk();

    Event::fake();

    publishDrafts($section)->assertOk();

    Event::assertDispatched('section.update.before');

    Event::assertDispatched('section.update.after');
});

it('should announce a discard for each section it reverts, before and after', function () {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>regret</p>'],
    ])->assertOk();

    Event::fake();

    discardDrafts($section)->assertOk();

    Event::assertDispatched('section.draft.discard.before');

    Event::assertDispatched('section.draft.discard.after');
});

it('should offer no endpoint that releases a single section', function (string $action) {
    $section = makeSection();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>staged</p>'],
    ])->assertOk();

    $response = postJson("admin/appearance/sections/{$section->id}/{$action}");

    expect($response->getStatusCode())->toBeGreaterThanOrEqual(400);

    expect($section->fresh()->translate(app()->getLocale())->draft_options)
        ->toBe(['html' => '<p>staged</p>']);
})->with(['publish', 'discard']);

it('should refuse to publish a theme that does not exist', function () {
    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.publish', 'no-such-theme'))->assertNotFound();
});

it('should announce nothing when there is no pending edit to publish', function () {
    makeSection();

    Event::fake();

    $this->loginAsAdmin();

    publishDrafts()->assertOk()->assertJsonPath('published', 0);

    Event::assertNotDispatched('section.update.after');
});

it('should announce a media upload, before and after', function () {
    $section = makeSection();

    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('slide.jpg', 40, 40),
    ])->assertOk();

    Event::assertDispatched('section.media.upload.before');

    Event::assertDispatched('section.media.upload.after');
});

it('should announce a reorder, before and after', function () {
    $first = makeSection();

    $second = makeSection();

    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.reorder'), [
        'sections' => [$second->id, $first->id],
    ])->assertOk();

    Event::assertDispatched('section.reorder.before');

    Event::assertDispatched('section.reorder.after');
});

it('should hold a new section back from the storefront until it is published', function () {
    $channel = core()->getCurrentChannel();

    $this->loginAsAdmin();

    $id = postJson(route('admin.appearance.sections.store', [
        'code' => $channel->theme ?: 'default',
        'channel' => $channel->id,
    ]), [
        'name' => 'Fresh Section',
        'type' => Section::STATIC_CONTENT,
    ])->assertOk()->json('section.id');

    $section = Section::find($id);

    expect((bool) $section->status)->toBeFalse()
        ->and($section->draft_status)->toBeTrue()
        ->and(app(SectionRepository::class)->hasDraft($section))->toBeTrue();

    publishDrafts($section)->assertOk();

    expect((bool) $section->refresh()->status)->toBeTrue()
        ->and($section->draft_status)->toBeNull();
});

it('should hold a status change until it is published', function () {
    $section = makeSection(['status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.status', $section->id), ['status' => false])
        ->assertOk()
        ->assertJsonPath('has_draft', true);

    expect((bool) $section->refresh()->status)->toBeTrue()
        ->and($section->draft_status)->toBeFalse();

    publishDrafts($section)->assertOk();

    expect((bool) $section->refresh()->status)->toBeFalse();
});

it('should hold a reorder until it is published', function () {
    $first = makeSection();

    $second = makeSection();

    $order = [$second->id, $first->id];

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.reorder'), ['sections' => $order])->assertOk();

    expect($second->refresh()->draft_sort_order)->toBe(1)
        ->and($second->sort_order)->not->toBe(1);

    publishDrafts($second)->assertOk();

    expect($second->refresh()->sort_order)->toBe(1)
        ->and($second->draft_sort_order)->toBeNull();
});

it('should stage only the sections a reorder actually moved', function () {
    $first = makeSection(['sort_order' => 5]);

    $second = makeSection(['sort_order' => 6]);

    $third = makeSection(['sort_order' => 9]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.reorder'), [
        'sections' => [$first->id, $third->id, $second->id],
    ])
        ->assertOk()
        ->assertJsonPath('pending.'.$first->id, false)
        ->assertJsonPath('pending.'.$third->id, true)
        ->assertJsonPath('pending.'.$second->id, true);

    expect($first->refresh()->draft_sort_order)->toBeNull();
});

it('should put a staged change back where it was when it is discarded', function () {
    $section = makeSection(['status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.status', $section->id), ['status' => false])->assertOk();

    discardDrafts($section)
        ->assertOk()
        ->assertJsonPath('discarded', 1);

    expect($section->refresh()->draft_status)->toBeNull()
        ->and((bool) $section->status)->toBeTrue();
});

it('should delete the uploads a discarded draft brought with it', function () {
    Storage::fake();

    $section = makeSection(['type' => Section::IMAGE_CAROUSEL]);

    $this->loginAsAdmin();

    $published = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('kept.jpg', 40, 40),
    ])->json('path');

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['images' => [['image' => $published, 'link' => '', 'title' => 'kept']]],
    ])->assertOk();

    publishDrafts($section)->assertOk();

    $drafted = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('thrown-away.jpg', 40, 40),
    ])->json('path');

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['images' => [['image' => $drafted, 'link' => '', 'title' => 'draft']]],
    ])->assertOk();

    discardDrafts($section)->assertOk();

    Storage::assertMissing(str_replace('storage/', '', $drafted));

    Storage::assertExists(str_replace('storage/', '', $published));
});

it('should delete the upload a published draft replaced', function () {
    Storage::fake();

    $section = makeSection(['type' => Section::IMAGE_CAROUSEL]);

    $this->loginAsAdmin();

    $stage = function (string $name) use ($section) {
        $path = postJson(route('admin.appearance.sections.media', $section->id), [
            'file' => UploadedFile::fake()->image($name, 40, 40),
        ])->json('path');

        postJson(route('admin.appearance.sections.draft', $section->id), [
            'options' => ['images' => [['image' => $path, 'link' => '', 'title' => $name]]],
        ])->assertOk();

        publishDrafts($section)->assertOk();

        return $path;
    };

    $old = $stage('old.jpg');

    $new = $stage('new.jpg');

    Storage::assertMissing(str_replace('storage/', '', $old));

    Storage::assertExists(str_replace('storage/', '', $new));
});

it('should keep an upload that only custom html points at', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    $path = postJson(route('admin.appearance.sections.media', $section->id), [
        'file' => UploadedFile::fake()->image('in-html.jpg', 40, 40),
    ])->json('path');

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<img src="/'.$path.'" alt="in html">', 'css' => ''],
    ])->assertOk();

    publishDrafts($section)->assertOk();

    Storage::assertExists(str_replace('storage/', '', $path));
});

it('should clear a section media directory however the section is deleted', function (string $how) {
    Storage::fake();

    $section = makeSection(['type' => Section::IMAGE_CAROUSEL]);

    $directory = 'themes/'.$section->theme_code.'/sections/'.$section->id;

    Storage::put($directory.'/probe.webp', 'x');

    Storage::assertExists($directory.'/probe.webp');

    match ($how) {
        'model' => $section->delete(),
        'repository' => app(SectionRepository::class)->delete($section->id),
        'collection' => Section::where('id', $section->id)->get()->each->delete(),
    };

    expect(Storage::exists($directory))->toBeFalse();
})->with(['model', 'repository', 'collection']);

it('should copy a section as a pending change rather than straight onto the storefront', function () {
    $section = makeSection(['status' => 1]);

    $this->loginAsAdmin();

    $copyId = postJson(route('admin.appearance.sections.duplicate', $section->id))
        ->assertOk()
        ->assertJsonPath('section.has_draft', true)
        ->json('section.id');

    $copy = Section::find($copyId);

    expect((bool) $copy->status)->toBeFalse()
        ->and($copy->draft_status)->toBeTrue()
        ->and($copy->draft_sort_order)->toBeNull();

    expect(app(SectionRepository::class)->getRenderable($copy->channel_id, $copy->theme_code)->pluck('id'))
        ->not->toContain($copy->id);

    publishDrafts($copy)->assertOk();

    expect((bool) $copy->refresh()->status)->toBeTrue()
        ->and(app(SectionRepository::class)->getRenderable($copy->channel_id, $copy->theme_code)->pluck('id'))
        ->toContain($copy->id);
});
