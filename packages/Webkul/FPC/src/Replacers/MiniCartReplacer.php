<?php

namespace Webkul\FPC\Replacers;

use Spatie\ResponseCache\Replacers\Replacer;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Checkout\Facades\Cart;

class MiniCartReplacer implements Replacer
{
    /**
     * Replacement string.
     */
    protected string $replacementString = '\'<bagisto-response-cache-mini-cart>\'';

    /**
     * Prepare the response to be cached, which the view already did by writing the marker
     * on any page the cache is going to store.
     */
    public function prepareResponseToCache(Response $response): void {}

    /**
     * Seed the mini cart from the current session, so a cached page never hands one
     * visitor the cart state of another.
     */
    public function replaceInCachedResponse(Response $response): void
    {
        $content = $response->getContent();

        if (! str_contains($content, $this->replacementString)) {
            return;
        }

        $response->setContent(str_replace(
            $this->replacementString,
            $this->seed(),
            $content
        ));
    }

    /**
     * What the mini cart starts from: an empty cart is seeded outright, so only a visitor
     * who has items pays for the request that fetches them.
     */
    protected function seed(): string
    {
        return Cart::getCart()?->items->isNotEmpty()
            ? 'null'
            : json_encode(['items_qty' => 0, 'items' => []]);
    }
}
