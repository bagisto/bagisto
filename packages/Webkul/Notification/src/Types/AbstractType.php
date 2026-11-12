<?php

namespace Webkul\Notification\Types;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-notification';

    /**
     * The colour of the icon: blue, green, amber, red, purple or gray.
     */
    protected string $tone = 'blue';

    /**
     * Build the data kept with the notification from what it is about.
     */
    abstract public function data(mixed $subject): array;

    /**
     * The headline of the notification, in the reader's language.
     */
    abstract public function title(array $data): string;

    /**
     * Whether the subject is worth notifying about, letting a type skip some of the events it listens to.
     */
    public function shouldNotify(mixed $subject): bool
    {
        return true;
    }

    /**
     * The users the notification is addressed to, or null to address everyone in its audience.
     *
     * @return iterable<Model>|null
     */
    public function recipients(mixed $subject): ?iterable
    {
        return null;
    }

    /**
     * The line shown under the headline, if any.
     */
    public function message(array $data): ?string
    {
        return null;
    }

    /**
     * Where opening the notification takes the reader, if anywhere.
     */
    public function url(array $data): ?string
    {
        return null;
    }

    /**
     * The icon class for this notification.
     */
    public function icon(array $data): string
    {
        return $this->icon;
    }

    /**
     * The colour of the icon for this notification.
     */
    public function tone(array $data): string
    {
        return $this->tone;
    }
}
