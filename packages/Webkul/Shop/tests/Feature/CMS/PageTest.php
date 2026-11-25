<?php

use Webkul\CMS\Models\Page;
use Webkul\CMS\Models\PageTranslation;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;

use function Pest\Laravel\get;

/**
 * Create a page on each of the default channel and a new one at the same url key, returning the new channel.
 */
function cmsPagesSharingUrlKey(string $urlKey): Channel
{
    $otherChannel = Channel::factory()->create(['hostname' => 'http://cms-shared-url-key.test']);

    $pages = [
        core()->getDefaultChannel()->id => 'Default channel page',
        $otherChannel->id => 'Other channel page',
    ];

    foreach ($pages as $channelId => $content) {
        $page = Page::factory()->create();

        PageTranslation::factory()->create([
            'cms_page_id' => $page->id,
            'url_key' => $urlKey,
            'html_content' => '<p>'.$content.'</p>',
            'locale' => app()->getLocale(),
        ]);

        $page->channels()->sync([$channelId]);
    }

    return $otherChannel;
}

// ============================================================================
// Shared URL Keys
// ============================================================================

it('should show the default channel its own CMS page at a URL key another channel shares', function () {
    cmsPagesSharingUrlKey('shared-url-key-page');

    Core::clearResolvedInstance(BaseCore::class);

    get('page/shared-url-key-page')
        ->assertOk()
        ->assertSee('Default channel page', false)
        ->assertDontSee('Other channel page', false);
});

it('should show another channel its own CMS page at a URL key the default channel shares', function () {
    $otherChannel = cmsPagesSharingUrlKey('shared-url-key-page');

    Core::clearResolvedInstance(BaseCore::class);

    get($otherChannel->hostname.'/page/shared-url-key-page')
        ->assertOk()
        ->assertSee('Other channel page', false)
        ->assertDontSee('Default channel page', false);
});
