<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Webkul\Admin\CommandPalette\CommandPalette;
use Webkul\Admin\CommandPalette\RecordSources;

class CommandPaletteController extends Controller
{
    /**
     * How long a built index is trusted for, in seconds.
     */
    const CACHE_TTL = 900;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CommandPalette $commandPalette,
        protected RecordSources $recordSources,
    ) {}

    /**
     * Everything the signed in admin may reach, for the palette to search.
     *
     * The tree is cached per role and locale; record sources are named for it to search live.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'data' => Cache::remember(
                $this->cacheKey(),
                self::CACHE_TTL,
                fn () => $this->commandPalette->items(),
            ),
            'sources' => $this->recordSources->all(),
        ]);
    }

    /**
     * The key an admin's index is held under.
     *
     * Keyed by what the role may reach, so a permission change takes effect at once.
     */
    protected function cacheKey(): string
    {
        $role = auth()->guard('admin')->user()?->role;

        return implode('.', [
            'command_palette',
            md5(json_encode([$role?->permission_type, $role?->permissions])),
            app()->getLocale(),
        ]);
    }
}
