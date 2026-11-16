<?php

namespace Webkul\Admin\SystemHealth;

use Illuminate\Support\Facades\Cache;
use Throwable;
use Webkul\Admin\Notifications\AdminNotifications;
use Webkul\Admin\SystemHealth\Checks\AbstractCheck;
use Webkul\Notification\Contracts\Notification;
use Webkul\Notification\Notifier;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Notification\TypeRegistry;

class SystemHealth
{
    /**
     * The cache key held while the system has been inspected within the interval.
     */
    public const INSPECTED_KEY = 'admin:system-health:inspected';

    /**
     * The cache key holding how many checks needed attention at the last examination.
     */
    public const PROBLEMS_KEY = 'admin:system-health:problems';

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Notifier $notifier,
        protected NotificationRepository $notificationRepository,
        protected TypeRegistry $typeRegistry
    ) {}

    /**
     * Inspect the system unless it was inspected within the configured interval.
     */
    public function inspectOccasionally(): void
    {
        if (Cache::add(self::INSPECTED_KEY, true, (int) config('system_health.interval', 300))) {
            $this->inspect();
        }
    }

    /**
     * Run every check, raising one notification per new problem and resolving it once the check passes.
     */
    public function inspect(): void
    {
        $this->examine(fn (string $type, AbstractCheck $check, array $standing) => $this->settle(
            $type,
            $check,
            $standing['state'] === 'failing'
        ));
    }

    /**
     * Where every check stands right now: healthy, failing, unconfirmed, or idle when the store does not
     * use it. A failing check carries what to do about it, and where.
     *
     * @return list<array{name: string, state: string, message: ?string, url: ?string}>
     */
    public function report(): array
    {
        return $this->examine();
    }

    /**
     * How many checks were failing or unconfirmed when the system was last examined.
     */
    public function problems(): int
    {
        return (int) Cache::get(self::PROBLEMS_KEY, 0);
    }

    /**
     * How many checks of a report stand in each state.
     *
     * @param  list<array{state: string}>  $report
     * @return array{failing: int, unconfirmed: int, healthy: int, idle: int}
     */
    public function tally(array $report): array
    {
        return array_merge(
            ['failing' => 0, 'unconfirmed' => 0, 'healthy' => 0, 'idle' => 0],
            collect($report)->countBy('state')->all()
        );
    }

    /**
     * The percentage of the checks in use that are working; full when the store uses none.
     *
     * @param  array{failing: int, unconfirmed: int, healthy: int, idle: int}  $tally
     */
    public function score(array $tally): int
    {
        $inUse = $tally['failing'] + $tally['unconfirmed'] + $tally['healthy'];

        return $inUse ? (int) floor($tally['healthy'] / $inUse * 100) : 100;
    }

    /**
     * The worst state any check stands in, ignoring those not in use.
     *
     * @param  array{failing: int, unconfirmed: int, healthy: int, idle: int}  $tally
     */
    public function overall(array $tally): string
    {
        return match (true) {
            $tally['failing'] > 0 => 'failing',
            $tally['unconfirmed'] > 0 => 'unconfirmed',
            default => 'healthy',
        };
    }

    /**
     * Run every check once, hand each standing to the callback, and remember how many need attention.
     * A check that throws is reported and skipped, so one cannot hide the others.
     *
     * @return list<array{name: string, state: string, message: ?string, url: ?string}>
     */
    protected function examine(?callable $each = null): array
    {
        $report = [];

        foreach (config('system_health.checks', []) as $type => $class) {
            try {
                $check = app($class);

                $report[] = $standing = $this->describe($type, $check);

                if ($each) {
                    $each($type, $check, $standing);
                }
            } catch (Throwable $e) {
                report($e);
            }
        }

        $tally = $this->tally($report);

        Cache::forever(self::PROBLEMS_KEY, $tally['failing'] + $tally['unconfirmed']);

        return $report;
    }

    /**
     * One check's standing, with its notification type's words for the problem when it fails.
     *
     * @return array{name: string, state: string, message: ?string, url: ?string}
     */
    protected function describe(string $type, AbstractCheck $check): array
    {
        $failing = $check->applies() && $check->failing();

        $problem = $this->typeRegistry->instance($type);

        $data = $problem->data($check->context());

        return [
            'name' => $check->name(),
            'state' => match (true) {
                $failing => 'failing',
                $check->unconfirmed() => 'unconfirmed',
                $check->applies() => 'healthy',
                default => 'idle',
            },
            'message' => $failing ? $problem->message($data) : null,
            'url' => $failing ? $problem->url($data) : null,
        ];
    }

    /**
     * Bring a type's notification in line with whether its check is failing.
     */
    protected function settle(string $type, AbstractCheck $check, bool $failing): void
    {
        $open = $this->openProblem($type);

        if ($failing) {
            if (! $open) {
                $this->notifier->notify($type, $check->context());
            }

            return;
        }

        if ($open) {
            $this->notificationRepository->update([
                'data' => array_merge($open->data ?? [], ['resolved_at' => now()->toIso8601String()]),
            ], $open->id);
        }
    }

    /**
     * The type's newest notification while its problem is still unresolved.
     */
    protected function openProblem(string $type): ?Notification
    {
        $latest = $this->notificationRepository->latestOf($type, AdminNotifications::AUDIENCE);

        if (
            ! $latest
            || ! empty($latest->data['resolved_at'])
        ) {
            return null;
        }

        return $latest;
    }
}
