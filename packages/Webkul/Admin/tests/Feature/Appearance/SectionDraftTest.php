<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Models\Section;
use Webkul\Theme\Repositories\SectionRepository;

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

    postJson(route('admin.appearance.sections.publish', $section->id))
        ->assertOk()
        ->assertJsonPath('has_draft', false);

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

    postJson(route('admin.appearance.sections.discard', $section->id))
        ->assertOk()
        ->assertJsonPath('has_draft', false);

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

it('should render the preview for a signed in admin', function () {
    makeSection();

    $this->loginAsAdmin();

    get(route('shop.appearance.preview'))->assertOk();
});

it('should copy a section including its translated options', function () {
    $section = makeSection(['name' => 'Hero']);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.duplicate', $section->id))->assertOk();

    $copy = Section::query()->where('id', '!=', $section->id)->latest('id')->first();

    expect($copy->name)->toContain('Hero');

    expect($copy->status)->toBe(0);

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

    /**
     * Sections belong to a theme, so with none requested the editor falls back to the
     * theme the current channel runs rather than showing a themeless listing.
     */
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

    expect($path)->toStartWith('storage/section/'.$section->id.'/')
        ->and($path)->toEndWith('.webp')
        ->and($response->json('type'))->toBe('image');

    Storage::assertExists(str_replace('storage/', '', $path));
});

it('should store an uploaded video as it was given rather than as an image', function () {
    Storage::fake();

    $section = makeSection();

    $this->loginAsAdmin();

    /**
     * A video cannot go through the image conversion the other uploads use, so it is
     * kept in its own format.
     */
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

    /**
     * Two contradicting X-Frame-Options headers are what made the frame refuse to
     * connect, so the blanket one is dropped and frame-ancestors carries the rule.
     */
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
    /**
     * `framable` lives in the request attribute bag, which is internal to the framework
     * and never populated from the HTTP message. Reading it with `$request->get()` or
     * `input()` instead would fall through to the query string and hand any visitor a
     * clickjacking switch, so the vectors are asserted rather than assumed.
     */
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

    /**
     * The attribute is set per request, so a framable preview must not leave the next
     * page framable for the same session.
     */
    get(route('shop.appearance.preview'))->assertOk();

    expect(get(route('shop.home.index'))->headers->get('X-Frame-Options'))->toBe('DENY');
});

it('should stop the preview navigating itself onto a page that cannot be framed', function () {
    $this->loginAsAdmin();

    /**
     * Only the preview route is framable, so a link followed inside the frame lands on a
     * refusal. The bridge therefore has to swallow clicks on links and form submits.
     */
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

    /**
     * The home page loops every section but only draws four of the types; footer links
     * and service promises are drawn by the layout. Marking a section in both places
     * leaves an empty duplicate, and the editor highlights whichever comes first.
     */
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

    /**
     * The layout draws the footer and the services from the current channel, so previewing
     * another one has to switch it; otherwise a channel with nothing of its own borrows
     * whatever the hostname happens to resolve to.
     */
    expect($marksIn($other->id))->toBeEmpty()
        ->and($marksIn($current->id))->not->toBeEmpty();
});
