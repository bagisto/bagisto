<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

abstract class SystemProblem extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-configuration';

    /**
     * The colour of the icon while the problem stands.
     */
    protected string $tone = 'red';

    /**
     * The translation key segment holding this problem's title and message.
     */
    abstract protected function name(): string;

    /**
     * Leave the problem unread for everyone: an admin's request finds it, but does not cause it.
     */
    public function shouldMarkReadForCauser(): bool
    {
        return false;
    }

    /**
     * Keep the check's details, with the problem marked as unresolved.
     */
    public function data(mixed $context): array
    {
        return array_merge((array) $context, ['resolved_at' => null]);
    }

    /**
     * The headline naming the problem, marked as resolved once it is.
     */
    public function title(array $data): string
    {
        $title = trans("admin::app.notifications.types.{$this->name()}.title");

        if (empty($data['resolved_at'])) {
            return $title;
        }

        return trans('admin::app.notifications.types.system.resolved-title', ['title' => $title]);
    }

    /**
     * What to do about the problem while it stands. A resolved one needs no instruction.
     */
    public function message(array $data): ?string
    {
        if (! empty($data['resolved_at'])) {
            return null;
        }

        return trans("admin::app.notifications.types.{$this->name()}.message", array_filter($data, 'is_scalar'));
    }

    /**
     * Red while the problem stands, green once it is resolved.
     */
    public function tone(array $data): string
    {
        return empty($data['resolved_at']) ? $this->tone : 'green';
    }
}
