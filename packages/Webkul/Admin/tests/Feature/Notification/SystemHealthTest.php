<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\Events\JobPopping;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Webkul\Admin\SystemHealth\Checks\ExchangeRatesCheck;
use Webkul\Admin\SystemHealth\Checks\ProductIndexCheck;
use Webkul\Admin\SystemHealth\Checks\QueueWorkerCheck;
use Webkul\Admin\SystemHealth\Checks\SchedulerCheck;
use Webkul\Admin\SystemHealth\Heartbeat;
use Webkul\Admin\SystemHealth\SystemHealth;
use Webkul\Core\Enums\SupportedFilesystemEnum;
use Webkul\Core\Mail\Transport\DynamicMailTransport;
use Webkul\Core\Models\Currency;
use Webkul\Notification\Models\Notification;
use Webkul\Notification\TypeRegistry;
use Webkul\Product\Enums\SearchEngineStatusEnum;
use Webkul\Product\Services\Search\SearchEngineAvailability;

use function Pest\Laravel\get;
use function Pest\Laravel\travel;

dataset('worker polls', [
    'a queue:work loop' => [fn () => new Looping('database', 'default')],
    'a queue:listen fetch from an empty queue' => [fn () => new JobPopping('database', 'default')],
]);

/**
 * The notifications raised for a system problem, oldest first.
 */
function systemProblems(string $type): Collection
{
    return Notification::query()->where('type', $type)->oldest('id')->get();
}

/**
 * Inspect the system, wait past every silence limit, and inspect it again.
 */
function inspectAfterLongSilence(): void
{
    app(SystemHealth::class)->inspect();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    app(SystemHealth::class)->inspect();
}

beforeEach(function () {
    Notification::query()->where('type', 'like', 'system.%')->delete();

    config(['queue.default' => 'database']);

    app(Heartbeat::class)->beat(SchedulerCheck::HEARTBEAT);
});

// ============================================================================
// Queue Worker
// ============================================================================

it('should report a queue worker that has stayed silent past its limit', function () {
    inspectAfterLongSilence();

    $problems = systemProblems('system.queue_worker_stopped');

    expect($problems)->toHaveCount(1)
        ->and($problems->first()->audience)->toBe('admin')
        ->and($problems->first()->data)->toMatchArray(['connection' => 'database', 'resolved_at' => null]);
});

it('should give a worker that never beat its full limit before reporting it', function () {
    app(SystemHealth::class)->inspect();

    travel(QueueWorkerCheck::SILENCE_LIMIT - 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.queue_worker_stopped'))->toBeEmpty();
});

it('should not expect a worker while the queue runs jobs in sync', function () {
    config(['queue.default' => 'sync']);

    inspectAfterLongSilence();

    expect(systemProblems('system.queue_worker_stopped'))->toBeEmpty();
});

it('should stay quiet while the worker keeps beating', function () {
    app(SystemHealth::class)->inspect();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    app(Heartbeat::class)->beat(QueueWorkerCheck::HEARTBEAT);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.queue_worker_stopped'))->toBeEmpty();
});

it('should report a standing problem only once', function () {
    inspectAfterLongSilence();

    travel(1)->hours();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.queue_worker_stopped'))->toHaveCount(1);
});

it('should resolve the problem once the worker beats again, and report it anew when it returns', function () {
    inspectAfterLongSilence();

    app(Heartbeat::class)->beat(QueueWorkerCheck::HEARTBEAT);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.queue_worker_stopped')->first()->data['resolved_at'])->not->toBeNull();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    app(SystemHealth::class)->inspect();

    $problems = systemProblems('system.queue_worker_stopped');

    expect($problems)->toHaveCount(2)
        ->and($problems->last()->data['resolved_at'])->toBeNull();
});

it('should beat whenever a worker polls the queue', function (object $event) {
    app(Heartbeat::class)->silence(QueueWorkerCheck::HEARTBEAT);

    travel(2)->minutes();

    expect(app(Heartbeat::class)->silence(QueueWorkerCheck::HEARTBEAT))->toBe(120);

    app('events')->dispatch($event);

    expect(app(Heartbeat::class)->silence(QueueWorkerCheck::HEARTBEAT))->toBe(0);
})->with('worker polls');

// ============================================================================
// Failed Jobs
// ============================================================================

it('should report failed jobs with their count, and resolve once they are cleared', function () {
    app('queue.failer')->flush();

    app('queue.failer')->log('database', 'default', json_encode(['uuid' => (string) Str::uuid()]), new RuntimeException('Failed.'));

    app(SystemHealth::class)->inspect();

    $problem = systemProblems('system.failed_jobs')->sole();

    expect($problem->data['count'])->toBe(1);

    app('queue.failer')->flush();

    app(SystemHealth::class)->inspect();

    expect($problem->refresh()->data['resolved_at'])->not->toBeNull();
});

// ============================================================================
// Scheduler
// ============================================================================

it('should report a scheduler that has stayed silent past its limit', function () {
    config(['queue.default' => 'sync']);

    travel(SchedulerCheck::SILENCE_LIMIT + 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.scheduler_stopped'))->toHaveCount(1);
});

it('should schedule the scheduler heartbeat every minute', function () {
    $event = collect(app(Schedule::class)->events())
        ->first(fn ($event) => $event->description === 'admin:system-health:scheduler-heartbeat');

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('* * * * *');
});

// ============================================================================
// Mail Configuration
// ============================================================================

it('should report mail left without an SMTP host', function () {
    config(['mail.default' => 'bagisto-dynamic-smtp']);

    $this->setConfig('emails.configure.smtp.host', '');

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.mail_not_configured'))->toHaveCount(1);
});

it('should report the Brevo driver left without an API key', function () {
    config(['mail.default' => 'bagisto-dynamic-smtp']);

    $this->setConfig([
        'emails.configure.smtp.driver' => DynamicMailTransport::DRIVER_BREVO_API,
        'emails.configure.smtp.brevo_api_key' => '',
    ]);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.mail_not_configured'))->toHaveCount(1);
});

it('should report a loopback SMTP host only on a production store', function (string $environment, int $expected) {
    config(['mail.default' => 'bagisto-dynamic-smtp']);

    $this->setConfig('emails.configure.smtp.host', '127.0.0.1');

    app()->detectEnvironment(fn () => $environment);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.mail_not_configured'))->toHaveCount($expected);
})->with([
    'production' => ['production', 1],
    'local' => ['local', 0],
]);

it('should accept a real SMTP host, and leave another mailer alone', function () {
    config(['mail.default' => 'bagisto-dynamic-smtp']);

    $this->setConfig('emails.configure.smtp.host', 'smtp.example.com');

    app(SystemHealth::class)->inspect();

    config(['mail.default' => 'log']);

    $this->setConfig('emails.configure.smtp.host', '');

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.mail_not_configured'))->toBeEmpty();
});

// ============================================================================
// Debug Mode
// ============================================================================

it('should report debug mode only on a production store', function (string $environment, int $expected) {
    config(['app.debug' => true]);

    app()->detectEnvironment(fn () => $environment);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.debug_mode_enabled'))->toHaveCount($expected);
})->with([
    'production' => ['production', 1],
    'local' => ['local', 0],
]);

// ============================================================================
// Application URL
// ============================================================================

it('should report an APP_URL whose host is not the one the store was reached on', function () {
    $this->loginAsAdmin();

    $dashboard = route('admin.dashboard.index');

    config(['app.url' => 'http://old-domain.example']);

    get($dashboard)->assertOk();

    $problem = systemProblems('system.app_url_mismatch')->sole();

    expect($problem->data)->toMatchArray([
        'url' => 'http://old-domain.example',
        'host' => parse_url($dashboard, PHP_URL_HOST),
    ]);
});

it('should accept an APP_URL that matches the address the store was reached on', function () {
    $this->loginAsAdmin();

    config(['app.url' => url('/')]);

    get(route('admin.dashboard.index'))->assertOk();

    expect(systemProblems('system.app_url_mismatch'))->toBeEmpty();
});

it('should never judge APP_URL outside a request', function () {
    config(['app.url' => 'http://old-domain.example']);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.app_url_mismatch'))->toBeEmpty();
});

// ============================================================================
// Search Engine
// ============================================================================

it('should report a search engine by what its server answers', function (string $status, int $expected) {
    $availability = Mockery::mock(SearchEngineAvailability::class);

    $availability->shouldReceive('isConnectable')->andReturn(true);

    $availability->shouldReceive('probe')->andReturn(['status' => $status]);

    app()->instance(SearchEngineAvailability::class, $availability);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.search_unreachable'))->toHaveCount($expected);
})->with([
    'unreachable' => [SearchEngineStatusEnum::UNREACHABLE->value, 1],
    'unauthorized' => [SearchEngineStatusEnum::UNAUTHORIZED->value, 1],
    'available' => [SearchEngineStatusEnum::AVAILABLE->value, 0],
]);

it('should not probe a search engine that has no server behind it', function () {
    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.search_unreachable'))->toBeEmpty();
});

// ============================================================================
// Remote Storage
// ============================================================================

it('should report a remote disk that is chosen but not in effect', function () {
    $this->setConfig(SupportedFilesystemEnum::CONFIG_KEY, SupportedFilesystemEnum::S3->value);

    config(['filesystems.default' => SupportedFilesystemEnum::LOCAL->value]);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.storage_unreachable'))->toHaveCount(1);
});

it('should accept a remote disk that is in effect, and local storage', function () {
    $this->setConfig(SupportedFilesystemEnum::CONFIG_KEY, SupportedFilesystemEnum::S3->value);

    config(['filesystems.default' => SupportedFilesystemEnum::S3->value]);

    app(SystemHealth::class)->inspect();

    $this->setConfig(SupportedFilesystemEnum::CONFIG_KEY, SupportedFilesystemEnum::LOCAL->value);

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.storage_unreachable'))->toBeEmpty();
});

// ============================================================================
// Broadcasting
// ============================================================================

it('should report a Reverb server that does not answer', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.options' => ['host' => 'reverb.example', 'port' => 8080, 'scheme' => 'http'],
    ]);

    Http::fake(fn () => throw new ConnectionException('Connection refused.'));

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.broadcast_unreachable'))->toHaveCount(1);
});

it('should count any reply from Reverb as reachable, and ignore other broadcasters', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.options' => ['host' => 'reverb.example', 'port' => 8080, 'scheme' => 'http'],
    ]);

    Http::fake(['*' => Http::response('Not found.', 404)]);

    app(SystemHealth::class)->inspect();

    config(['broadcasting.default' => 'log']);

    Http::fake(fn () => throw new ConnectionException('Connection refused.'));

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.broadcast_unreachable'))->toBeEmpty();
});

// ============================================================================
// Exchange Rates
// ============================================================================

it('should report scheduled exchange rates that have not updated in time', function () {
    Currency::factory()->count(2)->create();

    $this->setConfig('general.exchange_rates.schedule.enabled', 1);

    app(SystemHealth::class)->inspect();

    travel(ExchangeRatesCheck::SILENCE_LIMITS['daily'] + 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.exchange_rates_stale'))->toHaveCount(1);
});

it('should stay quiet while exchange rate updates keep succeeding', function () {
    Currency::factory()->count(2)->create();

    $this->setConfig('general.exchange_rates.schedule.enabled', 1);

    app(SystemHealth::class)->inspect();

    travel(ExchangeRatesCheck::SILENCE_LIMITS['daily'] + 60)->seconds();

    event('core.exchange_rate.update_rates.after');

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.exchange_rates_stale'))->toBeEmpty();
});

it('should not expect exchange rate updates while their schedule is off', function () {
    Currency::factory()->count(2)->create();

    $this->setConfig('general.exchange_rates.schedule.enabled', 0);

    app(SystemHealth::class)->inspect();

    travel(ExchangeRatesCheck::SILENCE_LIMITS['daily'] + 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.exchange_rates_stale'))->toBeEmpty();
});

// ============================================================================
// Product Index
// ============================================================================

it('should report a product left without a price index past the grace period', function () {
    $product = $this->createSimpleProduct();

    $product->price_indices()->delete();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.index_incomplete'))->toBeEmpty();

    travel(ProductIndexCheck::GRACE + 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(systemProblems('system.index_incomplete'))->toHaveCount(1);
});

// ============================================================================
// Presentation
// ============================================================================

it('should read as a problem while it stands and as resolved afterwards', function () {
    $type = app(TypeRegistry::class)->instance('system.queue_worker_stopped');

    $standing = ['connection' => 'database', 'resolved_at' => null];

    $resolved = ['connection' => 'database', 'resolved_at' => now()->toIso8601String()];

    expect($type->title($standing))->toBe(trans('admin::app.notifications.types.queue-worker-stopped.title'))
        ->and($type->message($standing))->toContain('database')
        ->and($type->tone($standing))->toBe('red')
        ->and($type->title($resolved))->toBe('Resolved: '.trans('admin::app.notifications.types.queue-worker-stopped.title'))
        ->and($type->message($resolved))->toBeNull()
        ->and($type->tone($resolved))->toBe('green');
});

it('should list system problems under their own group for an admin who may configure the store', function () {
    $this->loginAsAdmin();

    inspectAfterLongSilence();

    $titles = get(route('admin.notification.list', ['group' => 'system']))
        ->assertOk()
        ->json('data.*.title');

    expect($titles)->toContain(trans('admin::app.notifications.types.queue-worker-stopped.title'));
});

it('should link only a problem that has a screen to fix it on', function () {
    $this->loginAsAdmin();

    $linked = notifier()->notify('system.mail_not_configured')->first();

    $plain = notifier()->notify('system.queue_worker_stopped', ['connection' => 'database'])->first();

    $urls = collect(get(route('admin.notification.list', ['group' => 'system']))->assertOk()->json('data'))
        ->pluck('url', 'id');

    expect($urls[$linked->id])->toBe(route('admin.notification.open', $linked->id))
        ->and($urls[$plain->id])->toBeNull();

    get(route('admin.notification.open', $linked->id))
        ->assertRedirect(route('admin.configuration.index', ['slug' => 'emails', 'slug2' => 'configure']));

    get(route('admin.notification.open', $plain->id))->assertNotFound();
});

// ============================================================================
// Health Page
// ============================================================================

it('should report each check as healthy, failing, unconfirmed or idle', function () {
    $states = fn () => collect(app(SystemHealth::class)->report())->pluck('state', 'name');

    expect($states()['queue-worker'])->toBe('unconfirmed')
        ->and($states()['scheduler'])->toBe('healthy')
        ->and($states()['search-engine'])->toBe('idle');

    app(Heartbeat::class)->beat(QueueWorkerCheck::HEARTBEAT);

    expect($states()['queue-worker'])->toBe('healthy');

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    expect($states()['queue-worker'])->toBe('failing');

    config(['queue.default' => 'sync']);

    expect($states()['queue-worker'])->toBe('idle');
});

it('should give a failing check the words and the screen to fix it with', function () {
    config(['mail.default' => 'bagisto-dynamic-smtp']);

    $this->setConfig('emails.configure.smtp.host', '');

    $mail = collect(app(SystemHealth::class)->report())->firstWhere('name', 'mail');

    expect($mail)->toMatchArray([
        'state' => 'failing',
        'message' => trans('admin::app.notifications.types.mail-not-configured.message'),
        'url' => route('admin.configuration.index', ['slug' => 'emails', 'slug2' => 'configure']),
    ]);
});

it('should raise no notification by reporting', function () {
    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    app(SystemHealth::class)->report();

    expect(systemProblems('system.queue_worker_stopped'))->toBeEmpty();
});

it('should count the checks needing attention for the account menu, and show none while all is well', function () {
    $this->loginAsAdmin();

    $counter = '<!-- Problems Counter -->';

    app(Heartbeat::class)->beat(QueueWorkerCheck::HEARTBEAT);

    app(SystemHealth::class)->inspect();

    $healthy = app(SystemHealth::class)->problems();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    app(SystemHealth::class)->inspect();

    expect(app(SystemHealth::class)->problems())->toBe($healthy + 2);

    get(route('admin.dashboard.index'))->assertOk()->assertSee($counter, false);
});

it('should score the share of checks in use that work, and name the worst state among them', function () {
    $systemHealth = app(SystemHealth::class);

    $tally = $systemHealth->tally([
        ['state' => 'healthy'],
        ['state' => 'healthy'],
        ['state' => 'unconfirmed'],
        ['state' => 'idle'],
    ]);

    expect($tally)->toBe(['failing' => 0, 'unconfirmed' => 1, 'healthy' => 2, 'idle' => 1])
        ->and($systemHealth->score($tally))->toBe(66)
        ->and($systemHealth->overall($tally))->toBe('unconfirmed')
        ->and($systemHealth->score($systemHealth->tally([['state' => 'idle']])))->toBe(100)
        ->and($systemHealth->overall($systemHealth->tally([['state' => 'idle']])))->toBe('healthy');
});

it('should show the health page with every check and its state', function () {
    $this->loginAsAdmin();

    config(['queue.default' => 'sync']);

    get(route('admin.configuration.index', ['slug' => 'about', 'slug2' => 'system_health']))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.about.system-health.checks.queue-worker.title'))
        ->assertSeeText(trans('admin::app.configuration.index.about.system-health.checks.product-index.title'))
        ->assertSeeText(trans('admin::app.configuration.index.about.system-health.states.idle'));
});

it('should link the health page from the account menu only for an admin who may configure the store', function () {
    $link = route('admin.configuration.index', ['slug' => 'about', 'slug2' => 'system_health']);

    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))->assertOk()->assertSee($link);

    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.dashboard.index'))->assertOk()->assertDontSee($link);
});

// ============================================================================
// Trigger
// ============================================================================

it('should inspect on an admin request, at most once per interval', function () {
    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))->assertOk();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    get(route('admin.dashboard.index'))->assertOk();

    get(route('admin.dashboard.index'))->assertOk();

    expect(systemProblems('system.queue_worker_stopped'))->toHaveCount(1)
        ->and(Cache::has(SystemHealth::INSPECTED_KEY))->toBeTrue();
});

it('should leave a problem unread for the admin whose request found it', function () {
    $admin = $this->loginAsAdmin();

    get(route('admin.dashboard.index'))->assertOk();

    travel(QueueWorkerCheck::SILENCE_LIMIT + 60)->seconds();

    get(route('admin.dashboard.index'))->assertOk();

    $problem = systemProblems('system.queue_worker_stopped')->sole();

    expect($problem->reads()->where('reader_id', $admin->id)->exists())->toBeFalse();
});

it('should not inspect for a visitor who is not signed in', function () {
    get(route('admin.dashboard.index'));

    expect(Cache::has(SystemHealth::INSPECTED_KEY))->toBeFalse();
});
