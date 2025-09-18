<?php

namespace Webkul\FPC\Replacers;

use Spatie\ResponseCache\Replacers\Replacer;
use Symfony\Component\HttpFoundation\Response;

class FlashMessagesReplacer implements Replacer
{
    /**
     * Replacement string.
     */
    protected string $replacementString = '\'<bagisto-response-cache-session-flashes>\'';

    /**
     * Prepare the response to be cached by replacing flash messages with marker.
     */
    public function prepareResponseToCache(Response $response): void
    {
        $content = $response->getContent();

        // No need to replace existing flash messages since we’re already adding a placeholder by
        // checking the response cache enabled flag in the view. At the moment, there is no way
        // to make this change directly in the JS component.

        $response->setContent($content);
    }

    /**
     * Replace flash messages marker with actual flash messages JSON.
     */
    public function replaceInCachedResponse(Response $response): void
    {
        $content = $response->getContent();

        if (strpos($content, $this->replacementString) === false) {
            return;
        }

        $flashes = [];

        foreach (['success', 'warning', 'error', 'info'] as $type) {
            if (session()->has($type)) {
                $flashes[] = [
                    'type'    => $type,
                    'message' => session($type),
                ];
            }
        }

        $content = str_replace(
            $this->replacementString,
            collect($flashes)->toJson(),
            $content
        );

        $response->setContent($content);
    }
}
