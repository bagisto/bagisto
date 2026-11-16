<?php

namespace Webkul\Admin\Notifications\Types;

class StorageUnreachable extends SystemProblem
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-folder';

    /**
     * The file management configuration screen.
     */
    public function url(array $data): ?string
    {
        return route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => 'general']);
    }

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'storage-unreachable';
    }
}
