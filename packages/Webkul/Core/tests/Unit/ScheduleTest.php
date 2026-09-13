<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Repositories\CoreConfigRepository;

/**
 * Save the exchange rate schedule settings and read what a freshly built schedule holds for the update command.
 */
function scheduledExchangeRateUpdate(array $settings): ?Event
{
    foreach ($settings as $field => $value) {
        test()->setConfig('general.exchange_rates.schedule.'.$field, $value);
    }

    CacheGeneration::bump(CoreConfigRepository::class);

    app()->forgetInstance(Schedule::class);

    return collect(app(Schedule::class)->events())
        ->first(fn (Event $event) => str_contains($event->command, 'exchange-rate:update'));
}

// ============================================================================
// Exchange Rate Update
// ============================================================================

it('should not schedule the exchange rate update while the schedule is disabled', function () {
    expect(scheduledExchangeRateUpdate(['enabled' => '0', 'frequency' => 'daily', 'time' => '02:30']))->toBeNull();
});

it('should schedule the exchange rate update daily at midnight when no frequency or time is set', function () {
    expect(scheduledExchangeRateUpdate(['enabled' => '1', 'frequency' => '', 'time' => '']))
        ->not->toBeNull()
        ->expression->toBe('0 0 * * *');
});

it('should schedule the exchange rate update at the configured frequency and time', function (string $frequency, string $expression) {
    expect(scheduledExchangeRateUpdate(['enabled' => '1', 'frequency' => $frequency, 'time' => '02:30']))
        ->not->toBeNull()
        ->expression->toBe($expression);
})->with([
    'daily' => ['daily', '30 2 * * *'],
    'weekly on monday' => ['weekly', '30 2 * * 1'],
    'monthly on the first' => ['monthly', '30 2 1 * *'],
]);
