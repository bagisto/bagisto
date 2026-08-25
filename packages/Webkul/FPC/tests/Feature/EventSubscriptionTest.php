<?php

use Illuminate\Support\Facades\Event;
use Webkul\FPC\Listeners\Category;
use Webkul\FPC\Listeners\Channel;
use Webkul\FPC\Listeners\CoreConfig;
use Webkul\FPC\Listeners\Order;
use Webkul\FPC\Listeners\Page;
use Webkul\FPC\Listeners\Product;
use Webkul\FPC\Listeners\Refund;
use Webkul\FPC\Listeners\Review;
use Webkul\FPC\Listeners\Section;
use Webkul\FPC\Listeners\URLRewrite;

it('subscribes the page cache to the event', function (string $event, string $listener, string $method) {
    // Act
    $registered = Event::getRawListeners()[$event] ?? [];

    // Assert
    expect($registered)->toContain([$listener, $method]);
})->with([
    'product created' => ['catalog.product.create.after', Product::class, 'afterCreate'],
    'product updated' => ['catalog.product.update.after', Product::class, 'afterUpdate'],
    'product deleted' => ['catalog.product.delete.before', Product::class, 'beforeDelete'],
    'category created' => ['catalog.category.create.after', Category::class, 'afterCreate'],
    'category updated' => ['catalog.category.update.after', Category::class, 'afterUpdate'],
    'category deleted' => ['catalog.category.delete.before', Category::class, 'beforeDelete'],
    'review updated' => ['customer.review.update.after', Review::class, 'afterUpdate'],
    'review deleted' => ['customer.review.delete.before', Review::class, 'beforeDelete'],
    'order placed' => ['checkout.order.save.after', Order::class, 'afterCancelOrCreate'],
    'order cancelled' => ['sales.order.cancel.after', Order::class, 'afterCancelOrCreate'],
    'refund saved' => ['sales.refund.save.after', Refund::class, 'afterCreate'],
    'page updated' => ['cms.page.update.after', Page::class, 'afterUpdate'],
    'page deleted' => ['cms.page.delete.before', Page::class, 'beforeDelete'],
    'section created' => ['section.create.after', Section::class, 'afterCreate'],
    'section updated' => ['section.update.after', Section::class, 'afterUpdate'],
    'section deleted' => ['section.delete.before', Section::class, 'beforeDelete'],
    'channel updated' => ['core.channel.update.after', Channel::class, 'afterUpdate'],
    'configuration saved' => ['core.configuration.save.after', CoreConfig::class, 'afterUpdate'],
    'url rewrite updated' => ['marketing.search_seo.url_rewrites.update.after', URLRewrite::class, 'afterUpdate'],
    'url rewrite deleted' => ['marketing.search_seo.url_rewrites.delete.before', URLRewrite::class, 'beforeDelete'],
]);
