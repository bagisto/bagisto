<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Enums\SearchEngineStatusEnum;
use Webkul\Product\Services\Search\SearchEngineAvailability;

class SearchEngineController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected SearchEngineAvailability $availability,
    ) {}

    /**
     * Ask a search engine whether it is reachable.
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

        $verdict = $this->availability->probe($engine);

        $status = SearchEngineStatusEnum::tryFrom($verdict['status']) ?? SearchEngineStatusEnum::UNREACHABLE;

        return new JsonResponse(array_merge($verdict, [
            'success' => $status->isUsable(),
            'message' => trans("admin::app.configuration.index.search-engines.test-connection.statuses.{$status->value}", [
                'engine' => trans("admin::app.configuration.index.search-engines.engines.{$engine->value}"),
            ]),
        ]), $status->isUsable() ? 200 : 422);
    }
}
