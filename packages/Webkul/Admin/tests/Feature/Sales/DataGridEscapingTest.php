<?php

use Webkul\RMA\Models\RMAStatus;

use function Pest\Laravel\getJson;

it('escapes a grid value that a closure puts inside an html attribute', function () {
    /**
     * The grid strips tags from every value before the closures run, so an injected
     * element never survives. A quote does, and a value a closure interpolates into
     * an attribute can close it and open an event handler.
     */
    $status = RMAStatus::query()->create([
        'title' => 'Breakout',
        'status' => 1,
        'color' => 'red" onmouseover="alert(1)',
    ]);

    $this->loginAsAdmin();

    $records = collect(
        getJson(route('admin.sales.rma.statuses.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->assertOk()->json('records')
    );

    $cell = $records->firstWhere('id', $status->id)['color'];

    expect($cell)->toContain('style="background: red&quot; onmouseover=&quot;alert(1);"');

    expect($cell)->not->toContain('style="background: red" onmouseover="alert(1);"');

    $status->delete();
});
