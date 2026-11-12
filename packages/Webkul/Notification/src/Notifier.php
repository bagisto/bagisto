<?php

namespace Webkul\Notification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use Throwable;
use Webkul\Notification\Repositories\NotificationRepository;

class Notifier
{
    /**
     * Create a new notifier instance.
     */
    public function __construct(
        protected TypeRegistry $typeRegistry,
        protected NotificationRepository $notificationRepository
    ) {}

    /**
     * Notify a type's audience about a subject, once for everyone or once per recipient the type names.
     */
    public function notify(string $type, mixed $subject = []): Collection
    {
        $instance = $this->typeRegistry->instance($type);

        if (! $instance->shouldNotify($subject)) {
            return collect();
        }

        $audience = $this->typeRegistry->audienceOf($type)
            ?? throw new InvalidArgumentException("The notification type [{$type}] names no audience.");

        $data = $instance->data($subject);

        Event::dispatch('notification.create.before', $type);

        $notifications = collect($instance->recipients($subject) ?? [null])
            ->map(fn (?Model $recipient) => $this->notificationRepository->create([
                'type' => $type,
                'audience' => $audience,
                'data' => $data,
                'notifiable_type' => $recipient?->getMorphClass(),
                'notifiable_id' => $recipient?->getKey(),
            ]))
            ->values();

        $notifications->each(fn ($notification) => Event::dispatch('notification.create.after', $notification));

        return $notifications;
    }

    /**
     * Listen to the events each registered type names, notifying with the event's first argument.
     */
    public function listen(): void
    {
        foreach ($this->typeRegistry->all() as $type => $definition) {
            foreach ((array) ($definition['events'] ?? []) as $event) {
                Event::listen($event, fn (mixed $subject = null) => $this->notifyAfterCommit($type, $subject));
            }
        }
    }

    /**
     * Notify once the transaction the event fired in commits, reporting a failure rather than throwing it,
     * so a notification can never undo or fail the order, refund or other change that raised it.
     */
    protected function notifyAfterCommit(string $type, mixed $subject): void
    {
        DB::afterCommit(function () use ($type, $subject) {
            try {
                $this->notify($type, $subject);
            } catch (Throwable $e) {
                report($e);
            }
        });
    }
}
