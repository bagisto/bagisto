<?php

namespace Webkul\Notification\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Notification\Contracts\Notification;

class NotificationRepository extends Repository
{
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
     * The newest notification of a type raised for an audience, if any.
     */
    public function latestOf(string $type, string $audience): ?Notification
    {
        return $this->model->newQuery()
            ->where('audience', $audience)
            ->where('type', $type)
            ->latest('id')
            ->first();
    }

    /**
     * Mark a notification read for the reader.
     */
    public function markRead(int $notificationId, Model $reader): void
    {
        $this->readRepository()->markRead([$notificationId], $reader);
    }

    /**
     * Mark a notification unread for the reader.
     */
    public function markUnread(int $notificationId, Model $reader): void
    {
        $this->readRepository()->markUnread($notificationId, $reader);
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
            ->chunkById(500, fn ($notifications) => $this->readRepository()->markRead(
                $notifications->pluck('id')->all(),
                $reader
            ));
    }

    /**
     * Return the placed order notifications with their orders, filtered as the bell asked before notifications were typed.
     *
     * @deprecated Use paginateFor() and unreadCountsFor().
     */
    public function getParamsData(array $params): array
    {
        $query = $this->orderNotifications()->with('order');

        if (
            isset($params['status'])
            && $params['status'] != 'All'
        ) {
            $query->whereHas('order', fn (Builder $order) => $order->where('status', $params['status']));
        }

        if (
            isset($params['read'])
            && isset($params['limit'])
        ) {
            $this->readByAnyone($query, (bool) $params['read'])->limit($params['limit']);
        } elseif (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        return [
            'notifications' => $query->latest()->paginate($params['limit'] ?? 10),
            'status_counts' => $this->orderStatusCounts(),
        ];
    }

    /**
     * Return the placed order notifications with their orders.
     *
     * @deprecated Use paginateFor() and unreadCountsFor().
     *
     * @return array
     */
    public function getAll(array $params = [])
    {
        return [
            'notifications' => $this->orderNotifications()->with('order')->latest()->paginate($params['limit'] ?? 10),
            'status_counts' => $this->orderStatusCounts(),
        ];
    }

    /**
     * Count the placed order notifications no reader has read.
     *
     * @deprecated Use unreadCountsFor().
     */
    public function countUnreadOrderNotifications(): int
    {
        return $this->readByAnyone($this->orderNotifications(), false)->count();
    }

    /**
     * Find the placed order notification of an order.
     *
     * @deprecated Use findFor().
     */
    public function findByOrder(int $orderId): ?Notification
    {
        return $this->orderNotifications()->where('order_id', $orderId)->first();
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
     * The notifications that carry the deprecated order column.
     */
    protected function orderNotifications(): Builder
    {
        return $this->model->newQuery()->whereNotNull('order_id');
    }

    /**
     * How many placed order notifications there are for each status their orders are in.
     */
    protected function orderStatusCounts(): Collection
    {
        return $this->model->newQuery()
            ->join('orders', 'notifications.order_id', '=', 'orders.id')
            ->select('orders.status', DB::raw('COUNT(*) as status_count'))
            ->groupBy('orders.status')
            ->get();
    }

    /**
     * Narrow to the notifications some reader has read, or to those no reader has, as the shared flag once meant.
     */
    protected function readByAnyone(Builder $query, bool $read): Builder
    {
        return $read
            ? $query->whereHas('reads')
            : $query->whereDoesntHave('reads');
    }

    /**
     * The repository read marks are written through, resolved here so the constructor stays the parent's.
     */
    protected function readRepository(): NotificationReadRepository
    {
        return app(NotificationReadRepository::class);
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
