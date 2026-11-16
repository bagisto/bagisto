<?php

namespace Webkul\Admin\Notifications\Types;

class SearchUnreachable extends SystemProblem
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-search';

    /**
     * The search engine configuration screen.
     */
    public function url(array $data): ?string
    {
        return route('admin.configuration.index', ['slug' => 'search_engines', 'slug2' => 'general']);
    }

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'search-unreachable';
    }
}
