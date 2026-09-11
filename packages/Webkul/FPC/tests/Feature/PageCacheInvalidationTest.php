<?php

use Illuminate\Support\Facades\Event;
use Webkul\CMS\Models\Page;
use Webkul\CMS\Models\PageTranslation;

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->otherHostScope = $this->addChannelOnHost('shop-two.test');

    $this->secondScope = $this->addSecondScope();

    $this->page = Page::factory()->hasTranslations()->create();

    $this->path = '/page/'.$this->page->translations->first()->url_key;

    $this->guestPage = $this->cachePage($this->path);

    $this->otherScopePage = $this->cachePage($this->path, $this->secondScope);

    $this->otherHostPage = $this->cachePage($this->path, $this->otherHostScope, 'shop-two.test');

    $this->bystander = $this->cachePage('/page/some-other-page');
});

it('drops a cms page in every locale, currency and channel host when it is updated, and nothing else', function () {
    Event::dispatch('cms.page.update.after', $this->page);

    $this->assertPageNotCached($this->guestPage);

    $this->assertPageNotCached($this->otherScopePage, 'A second locale and currency kept the page as it was before the edit.');

    $this->assertPageNotCached($this->otherHostPage, 'A channel on its own domain kept the page as it was before the edit.');

    $this->assertPageCached($this->bystander, 'Editing one page emptied the cache of pages it has nothing to do with.');
});

it('drops a cms page in every locale, currency and channel host when it is deleted, and nothing else', function () {
    Event::dispatch('cms.page.delete.before', $this->page->id);

    $this->assertPageNotCached($this->guestPage);

    $this->assertPageNotCached($this->otherScopePage, 'A second locale and currency kept serving a deleted page.');

    $this->assertPageNotCached($this->otherHostPage, 'A channel on its own domain kept serving a deleted page.');

    $this->assertPageCached($this->bystander);
});

it('drops a cms page under the url key of each of its translations', function () {
    PageTranslation::factory()->create([
        'cms_page_id' => $this->page->id,
        'locale' => $this->secondLocale->code,
        'url_key' => 'about-us-translated',
    ]);

    $translated = $this->cachePage('/page/about-us-translated', $this->secondScope);

    Event::dispatch('cms.page.update.after', $this->page->fresh('translations'));

    $this->assertPageNotCached($translated, 'The page kept its old copy under the url key of another locale.');
});
