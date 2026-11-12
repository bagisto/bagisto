<?php

namespace Webkul\Notification;

use InvalidArgumentException;
use Webkul\Notification\Types\AbstractType;

class TypeRegistry
{
    /**
     * The type instances resolved so far, keyed by type.
     *
     * @var array<string, AbstractType>
     */
    protected array $instances = [];

    /**
     * Every registered type, keyed by type.
     *
     * @return array<string, array>
     */
    public function all(): array
    {
        return config('notification_types', []);
    }

    /**
     * Whether a type is registered.
     */
    public function has(string $type): bool
    {
        return isset($this->all()[$type]);
    }

    /**
     * The class that renders a type, built once per request.
     */
    public function instance(string $type): AbstractType
    {
        if (! $this->has($type)) {
            throw new InvalidArgumentException("The notification type [{$type}] is not registered.");
        }

        return $this->instances[$type] ??= app($this->all()[$type]['class']);
    }

    /**
     * The audience a type notifies, such as the admins.
     */
    public function audienceOf(string $type): ?string
    {
        return $this->all()[$type]['audience'] ?? null;
    }

    /**
     * The group a type is listed under.
     */
    public function groupOf(string $type): ?string
    {
        return $this->all()[$type]['group'] ?? null;
    }

    /**
     * The types that notify an audience.
     *
     * @return list<string>
     */
    public function forAudience(string $audience): array
    {
        return array_keys(array_filter(
            $this->all(),
            fn (array $definition) => ($definition['audience'] ?? null) === $audience
        ));
    }

    /**
     * The groups holding any of the given types, in their sort order.
     *
     * @param  list<string>  $types
     * @return array<string, array>
     */
    public function groupsOf(array $types): array
    {
        $groups = array_unique(array_filter(array_map(fn (string $type) => $this->groupOf($type), $types)));

        return collect(config('notification_groups', []))
            ->only($groups)
            ->sortBy(fn (array $group) => $group['sort'] ?? 0)
            ->all();
    }
}
