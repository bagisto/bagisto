<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Requests\NotificationFeedRequest;
use Webkul\Admin\Http\Resources\NotificationResource;
use Webkul\Admin\Notifications\AdminNotifications;
use Webkul\Notification\Contracts\Notification;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Notification\TypeRegistry;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected TypeRegistry $typeRegistry,
        protected AdminNotifications $adminNotifications
    ) {}

    /**
     * Display the notification feed.
     */
    public function index(): View
    {
        return view('admin::notifications.index');
    }

    /**
     * The admin's notifications, narrowed to a group or to those unread, with the unread count of every group.
     */
    public function list(NotificationFeedRequest $request): JsonResponse
    {
        $notifications = $this->notificationRepository->paginateFor(
            $this->admin(),
            AdminNotifications::AUDIENCE,
            $request->types(),
            $request->boolean('unread'),
            $request->integer('per_page', 10)
        );

        $unreadCounts = $this->unreadCounts();

        return new JsonResponse([
            'data' => NotificationResource::collection($notifications->items())->resolve(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
            'groups' => $this->groups($unreadCounts),
            'unread' => array_sum($unreadCounts),
        ]);
    }

    /**
     * Mark the notification read and take the admin to what it is about.
     */
    public function open(int $id): RedirectResponse
    {
        $notification = $this->visibleNotification($id);

        $this->notificationRepository->markRead($notification->id, $this->admin());

        $url = $this->typeRegistry->instance($notification->type)->url($notification->data ?? []);

        return redirect()->to($url ?? route('admin.notification.index'));
    }

    /**
     * Mark the notification read for the admin.
     */
    public function markRead(int $id): JsonResponse
    {
        $this->notificationRepository->markRead($this->visibleNotification($id)->id, $this->admin());

        return $this->unreadResponse();
    }

    /**
     * Mark the notification unread for the admin.
     */
    public function markUnread(int $id): JsonResponse
    {
        $this->notificationRepository->markUnread($this->visibleNotification($id)->id, $this->admin());

        return $this->unreadResponse();
    }

    /**
     * Mark every notification the admin may see read, or only those of one group.
     */
    public function readAll(NotificationFeedRequest $request): JsonResponse
    {
        $this->notificationRepository->markAllRead($this->admin(), AdminNotifications::AUDIENCE, $request->types());

        return $this->unreadResponse(trans('admin::app.notifications.marked-all-read'));
    }

    /**
     * The signed in admin, who reads the feed.
     */
    protected function admin(): Model
    {
        return auth()->guard('admin')->user();
    }

    /**
     * A notification the admin may see, or a not found response.
     */
    protected function visibleNotification(int $id): Notification
    {
        $notification = $this->notificationRepository->findFor(
            $id,
            $this->admin(),
            AdminNotifications::AUDIENCE,
            $this->adminNotifications->types()
        );

        abort_unless($notification, 404);

        return $notification;
    }

    /**
     * How many notifications of each type the admin may see are unread.
     *
     * @return array<string, int>
     */
    protected function unreadCounts(): array
    {
        return $this->notificationRepository->unreadCountsFor(
            $this->admin(),
            AdminNotifications::AUDIENCE,
            $this->adminNotifications->types()
        );
    }

    /**
     * The groups the admin may see, with how many notifications in each are unread.
     *
     * @param  array<string, int>  $unreadCounts
     */
    protected function groups(array $unreadCounts): array
    {
        return collect($this->adminNotifications->groups())
            ->map(fn (array $group, string $key) => [
                'key' => $key,
                'name' => trans($group['name']),
                'icon' => $group['icon'] ?? 'icon-notification',
                'unread' => array_sum(array_intersect_key($unreadCounts, array_flip($this->adminNotifications->typesIn($key)))),
            ])
            ->values()
            ->all();
    }

    /**
     * The admin's unread count, with a message when there is one to show.
     */
    protected function unreadResponse(?string $message = null): JsonResponse
    {
        return new JsonResponse(array_filter([
            'unread' => array_sum($this->unreadCounts()),
            'message' => $message,
        ], fn ($value) => ! is_null($value)));
    }
}
