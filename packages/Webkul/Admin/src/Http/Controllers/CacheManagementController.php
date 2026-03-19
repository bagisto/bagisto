<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Admin\Services\CacheManagerService;

class CacheManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CacheManagerService $cacheManager) {}

    /**
     * Execute a cache action and return JSON response.
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string',
        ]);

        $result = $this->cacheManager->execute($request->input('action'));

        return new JsonResponse([
            'success' => $result['success'],
            'message' => $result['message'],
            'output' => $result['output'],
            'command' => $result['command'],
        ], $result['success'] ? 200 : 422);
    }
}
