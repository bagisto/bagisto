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
     * The index is built once per role and locale, because the menu and the configuration
     * tree only change with a deployment. Record sources are named rather than indexed,
     * for the palette to search live as the operator types.
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
     * Keyed by what the role may reach rather than by the role itself, so a permission
     * change is reflected at once instead of when the entry expires.
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
