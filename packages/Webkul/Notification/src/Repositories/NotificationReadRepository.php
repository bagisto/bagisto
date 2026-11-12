<?php

namespace Webkul\Notification\Repositories;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Eloquent\Repository;
use Webkul\Notification\Contracts\NotificationRead;

class NotificationReadRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return NotificationRead::class;
    }

    /**
     * Record the reader as having read each of the notifications, a second read moving the time on.
     *
     * @param  list<int>  $notificationIds
     */
    public function markRead(array $notificationIds, Model $reader): void
    {
        $readAt = now();

        $this->model->newQuery()->upsert(
            array_map(fn (int $notificationId) => [
                'notification_id' => $notificationId,
                'reader_type' => $reader->getMorphClass(),
                'reader_id' => $reader->getKey(),
                'read_at' => $readAt,
            ], $notificationIds),
            ['notification_id', 'reader_type', 'reader_id'],
            ['read_at']
        );
    }

    /**
     * Forget that the reader has read the notification.
     */
    public function markUnread(int $notificationId, Model $reader): void
    {
        $this->model->newQuery()
            ->where('notification_id', $notificationId)
            ->where('reader_type', $reader->getMorphClass())
            ->where('reader_id', $reader->getKey())
            ->delete();
    }
}
