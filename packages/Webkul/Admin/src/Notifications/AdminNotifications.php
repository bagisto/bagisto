<?php

namespace Webkul\Admin\Notifications;

use Webkul\Notification\TypeRegistry;

class AdminNotifications
{
    /**
     * The audience the admin panel's notifications are raised for.
     */
    public const AUDIENCE = 'admin';

    /**
     * Create a new instance.
     */
    public function __construct(protected TypeRegistry $typeRegistry) {}

    /**
     * The admin types the signed in admin may see, as each type's permission allows.
     *
     * @return list<string>
     */
    public function types(): array
    {
        return array_values(array_filter(
            $this->typeRegistry->forAudience(self::AUDIENCE),
            fn (string $type) => $this->permits($type)
        ));
    }

    /**
     * The types the signed in admin may see that are listed under a group.
     *
     * @return list<string>
     */
    public function typesIn(string $group): array
    {
        return array_values(array_filter($this->types(), fn (string $type) => $this->typeRegistry->groupOf($type) === $group));
    }

    /**
     * The groups holding a type the signed in admin may see, in their sort order.
     *
     * @return array<string, array>
     */
    public function groups(): array
    {
        return $this->typeRegistry->groupsOf($this->types());
    }

    /**
     * Whether the signed in admin's role grants the type's permission, or the type asks for none.
     */
    protected function permits(string $type): bool
    {
        $permission = $this->typeRegistry->all()[$type]['permission'] ?? null;

        return empty($permission)
            || bouncer()->hasPermission($permission);
    }
}
