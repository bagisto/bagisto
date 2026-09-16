<?php

use Webkul\RMA\Models\RMAStatus;

use function Pest\Laravel\getJson;

// ============================================================================
// Attribute Escaping
// ============================================================================

it('should escape a quote in a grid value that a closure puts inside an html attribute', function () {
    $status = RMAStatus::query()->create([
        'title' => 'Breakout',
        'status' => 1,
        'color' => 'red" onmouseover="alert(1)',
    ]);

    $this->loginAsAdmin();

    $records = collect(
        getJson(route('admin.sales.rma.statuses.index', [
            'filters' => ['id' => [$status->id]],
        ]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->assertOk()->json('records')
    );

    $cell = $records->firstWhere('id', $status->id)['color'];

    expect($cell)->toContain('style="background: red&quot; onmouseover=&quot;alert(1);"')
        ->not->toContain('style="background: red" onmouseover="alert(1);"');
});
