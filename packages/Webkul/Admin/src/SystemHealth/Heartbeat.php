<?php

namespace Webkul\Admin\SystemHealth;

use Illuminate\Support\Facades\Cache;

class Heartbeat
{
    /**
     * The fewest seconds between two writes of the same heartbeat.
     */
    public const WRITE_INTERVAL = 15;

    /**
     * When this process last wrote each heartbeat, keyed by name.
     *
     * @var array<string, int>
     */
    protected array $written = [];

    /**
     * Record that the named process is alive, writing at most once per interval.
     */
    public function beat(string $name): void
    {
        $now = now()->getTimestamp();

        if ($now - ($this->written[$name] ?? 0) < self::WRITE_INTERVAL) {
            return;
        }

        Cache::forever($this->key($name), $now);

        $this->written[$name] = $now;
    }

    /**
     * Whether the named process has ever been heard from.
     */
    public function heard(string $name): bool
    {
        return Cache::has($this->key($name));
    }

    /**
     * Seconds since the named process last beat. One that never beat is timed from
     * the first time it was found silent, so a cleared cache raises no false alarm.
     */
    public function silence(string $name): int
    {
        $last = Cache::get($this->key($name))
            ?? Cache::rememberForever($this->key($name).':watching', fn () => now()->getTimestamp());

        return max(0, now()->getTimestamp() - (int) $last);
    }

    /**
     * The cache key a heartbeat is kept under.
     */
    protected function key(string $name): string
    {
        return "admin:system-health:heartbeat:{$name}";
    }
}
