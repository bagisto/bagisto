<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Enums\SearchEngineStatusEnum;
use Webkul\Product\Services\Search\SearchEngineAvailability;

class SearchEngineController extends Controller
{
    /**
     * The settings a connection may be tried through, so nothing else reaches the engine's
     * configuration from the request.
     */
    const TESTABLE = [
        'auth_type',
        'hosts',
        'cloud_id',
        'username',
        'password',
        'api_key',
        'index_prefix',
    ];

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected SearchEngineAvailability $availability,
    ) {}

    /**
     * Ask a search engine whether it is reachable, through the settings as they stand on
     * screen rather than as they were last saved.
     */
    public function testConnection(string $engine): JsonResponse
    {
        $engine = SearchEngineEnum::tryFrom($engine);

        if (
            ! $engine
            || ! $this->availability->isConnectable($engine)
        ) {
            abort(404);
        }

        $verdict = $this->availability->probe($engine, $this->submitted());

        $status = SearchEngineStatusEnum::tryFrom($verdict['status']) ?? SearchEngineStatusEnum::UNREACHABLE;

        return new JsonResponse(array_merge($verdict, [
            'success' => $status->isUsable(),
            'message' => trans("admin::app.configuration.index.search-engines.test-connection.statuses.{$status->value}", [
                'engine' => trans("admin::app.configuration.index.search-engines.engines.{$engine->value}"),
            ]),
        ]), $status->isUsable() ? 200 : 422);
    }

    /**
     * The engine's settings as the form currently holds them.
     * A field the form leaves out keeps its saved value.
     */
    protected function submitted(): array
    {
        $settings = request()->input('settings', []);

        if (! is_array($settings)) {
            return [];
        }

        return array_map(
            'strval',
            array_filter(
                array_intersect_key($settings, array_flip(self::TESTABLE)),
                fn ($value) => is_scalar($value)
            )
        );
    }
}
