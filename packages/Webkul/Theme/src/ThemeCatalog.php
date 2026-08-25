<?php

namespace Webkul\Theme;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Theme\Repositories\SectionRepository;

class ThemeCatalog
{
    /**
     * A theme registered in `config/themes.php` and currently used by a channel.
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * A theme registered in `config/themes.php` but not used by any channel.
     */
    public const STATUS_INSTALLED = 'installed';

    /**
     * A theme offered in the catalog that is not registered on this installation.
     */
    public const STATUS_AVAILABLE = 'available';

    /**
     * Create a new catalog instance.
     *
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected SectionRepository $sectionRepository
    ) {}

    /**
     * Every theme known to this installation, installed ones first.
     */
    public function all(): Collection
    {
        $installed = collect(config('themes.shop', []));

        $channels = $this->channelRepository->all();

        $entries = $this->catalogEntries();

        $codes = $installed->keys()->merge($entries->keys())->unique();

        return $codes
            ->map(fn ($code) => $this->build($code, $installed->get($code), $entries->get($code), $channels))
            ->sortBy(fn ($theme) => [$this->weight($theme['status']), $theme['name']])
            ->values();
    }

    /**
     * Find a single theme by its code.
     */
    public function find(string $code): ?array
    {
        return $this->all()->firstWhere('code', $code);
    }

    /**
     * Number of customizations a channel holds for its current theme, used to warn
     * before switching a channel over to a different theme.
     */
    public function sectionCount(int $channelId, string $themeCode): int
    {
        return $this->sectionRepository
            ->where('channel_id', $channelId)
            ->where('theme_code', $themeCode)
            ->count();
    }

    /**
     * Build a single catalog row out of the registered config and the catalog entry,
     * either of which may be missing.
     */
    protected function build(string $code, ?array $config, ?array $entry, Collection $channels): array
    {
        $isInstalled = ! is_null($config);

        $activeOn = $channels->where('theme', $code);

        return [
            'code' => $code,
            'name' => $entry['name'] ?? $config['name'] ?? $code,
            'author' => $entry['author'] ?? null,
            'version' => $entry['version'] ?? null,
            'url' => $entry['url'] ?? null,
            'demo_url' => $entry['demo_url'] ?? null,
            'screenshot' => $this->screenshotUrl($entry['screenshot'] ?? null),
            'rating' => $entry['rating'] ?? null,
            'tags' => $entry['tags'] ?? [],
            'description' => $entry['description'] ?? null,
            'is_installed' => $isInstalled,
            'active_on' => $activeOn->map(fn ($channel) => [
                'id' => $channel->id,
                'name' => $channel->name,
            ])->values()->toArray(),
            'status' => match (true) {
                ! $isInstalled => self::STATUS_AVAILABLE,
                $activeOn->isNotEmpty() => self::STATUS_ACTIVE,
                default => self::STATUS_INSTALLED,
            },
        ];
    }

    /**
     * Resolve a catalog screenshot to a url.
     *
     * Remote screenshots are used as given. Anything else is treated as an admin asset
     * path, so that a theme shipped with Bagisto can carry its own bundled image.
     */
    protected function screenshotUrl(?string $screenshot): ?string
    {
        if (blank($screenshot)) {
            return null;
        }

        if (Str::startsWith($screenshot, ['http://', 'https://'])) {
            return $screenshot;
        }

        return bagisto_asset($screenshot, 'admin');
    }

    /**
     * Catalog entries keyed by theme code.
     */
    protected function catalogEntries(): Collection
    {
        $catalog = require __DIR__.'/Resources/catalog.php';

        return collect($catalog)->keyBy('code');
    }

    /**
     * Sort weight, so that active themes lead and purchasable ones trail.
     */
    protected function weight(string $status): int
    {
        return match ($status) {
            self::STATUS_ACTIVE => 0,
            self::STATUS_INSTALLED => 1,
            default => 2,
        };
    }
}
