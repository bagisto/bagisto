<?php

namespace Webkul\Notification\Repositories;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Notification\Contracts\Notification;

class NotificationRepository extends Repository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(
        protected NotificationReadRepository $notificationReadRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Notification::class;
    }

    /**
     * The reader's notifications of the given types, newest first, each flagged with whether the reader has read it.
     *
     * @param  list<string>  $types
     */
    public function paginateFor(Model $reader, string $audience, array $types, bool $unreadOnly = false, int $perPage = 10): LengthAwarePaginator
    {
        return $this->visibleTo($reader, $audience, $types, $unreadOnly)
            ->withExists(['reads as read' => fn (Builder $query) => $this->readBy($query, $reader)])
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * How many notifications of each given type the reader has not read.
     *
     * @param  list<string>  $types
     * @return array<string, int>
     */
    public function unreadCountsFor(Model $reader, string $audience, array $types): array
    {
        return $this->visibleTo($reader, $audience, $types, true)
            ->toBase()
            ->select('type', DB::raw('COUNT(*) as unread_count'))
            ->groupBy('type')
            ->pluck('unread_count', 'type')
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    /**
     * A notification of the given types the reader may see, or null when it is for another audience or reader.
     *
     * @param  list<string>  $types
     */
    public function findFor(int $id, Model $reader, string $audience, array $types): ?Notification
    {
        return $this->visibleTo($reader, $audience, $types, false)->find($id);
    }

    /**
     * Mark a notification read for the reader.
     */
    public function markRead(int $notificationId, Model $reader): void
    {
        $this->notificationReadRepository->markRead([$notificationId], $reader);
    }

    /**
     * Mark a notification unread for the reader.
     */
    public function markUnread(int $notificationId, Model $reader): void
    {
        $this->notificationReadRepository->markUnread($notificationId, $reader);
    }

    /**
     * Mark every unread notification of the given types read for the reader.
     *
     * @param  list<string>  $types
     */
    public function markAllRead(Model $reader, string $audience, array $types): void
    {
        $this->visibleTo($reader, $audience, $types, true)
            ->select('id')
            ->chunkById(500, fn ($notifications) => $this->notificationReadRepository->markRead(
                $notifications->pluck('id')->all(),
                $reader
            ));
    }

    /**
     * The audience's notifications addressed to everyone or to the reader, narrowed to some types and to the unread.
     *
     * @param  list<string>  $types
     */
    protected function visibleTo(Model $reader, string $audience, array $types, bool $unreadOnly): Builder
    {
        return $this->model->newQuery()
            ->where('audience', $audience)
            ->whereIn('type', $types)
            ->where(fn (Builder $query) => $query
                ->whereNull('notifiable_id')
                ->orWhere(fn (Builder $addressed) => $addressed
                    ->where('notifiable_type', $reader->getMorphClass())
                    ->where('notifiable_id', $reader->getKey())))
            ->when($unreadOnly, fn (Builder $query) => $query->whereDoesntHave(
                'reads',
                fn (Builder $reads) => $this->readBy($reads, $reader)
            ));
    }

    /**
     * Narrow a query of read marks to the reader's.
     */
    protected function readBy(Builder $query, Model $reader): Builder
    {
        return $query->where('reader_type', $reader->getMorphClass())
            ->where('reader_id', $reader->getKey());
    }
}
