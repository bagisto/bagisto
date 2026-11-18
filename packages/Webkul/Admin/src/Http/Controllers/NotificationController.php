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
use Webkul\Notification\Models\Notification as NotificationModel;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Notification\TypeRegistry;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance. The last two are optional so a subclass passing only the repository still works.
     */
    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected ?TypeRegistry $typeRegistry = null,
        protected ?AdminNotifications $adminNotifications = null
    ) {
        $this->typeRegistry ??= app(TypeRegistry::class);

        $this->adminNotifications ??= app(AdminNotifications::class);
    }

    /**
     * Display the notification feed.
     *
     * @return View
     */
    public function index()
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
     * Mark the notification read and take the admin to what it is about. One that leads nowhere cannot be opened.
     */
    public function open(int $id): RedirectResponse
    {
        $notification = $this->visibleNotification($id);

        $url = $this->typeRegistry->instance($notification->type)->url($notification->data ?? []);

        abort_if(is_null($url), 404);

        $this->notificationRepository->markRead($notification->id, $this->admin());

        return redirect()->to($url);
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
     * The placed order notifications, in the shape the bell read before notifications were typed.
     *
     * @deprecated Use list().
     *
     * @return array
     */
    public function getNotifications()
    {
        if (! $this->readsOrderNotifications()) {
            return [
                'search_results' => [],
                'status_count' => '',
                'total_unread' => 0,
            ];
        }

        $params = request()->except('page');

        $searchResults = count($params)
            ? $this->notificationRepository->getParamsData($params)
            : $this->notificationRepository->getAll();

        return [
            'search_results' => $searchResults['notifications'],
            'status_count' => $searchResults['status_counts'],
            'total_unread' => $this->notificationRepository->countUnreadOrderNotifications(),
        ];
    }

    /**
     * Mark an order's notification read and take the admin to the order.
     *
     * @deprecated Use open().
     *
     * @param  int  $orderId
     * @return RedirectResponse
     */
    public function viewedNotifications($orderId)
    {
        abort_unless($this->readsOrderNotifications(), 401);

        $notification = $this->notificationRepository->findByOrder((int) $orderId);

        abort_unless($notification, 404);

        $this->notificationRepository->markRead($notification->id, $this->admin());

        return redirect()->route('admin.sales.orders.view', $orderId);
    }

    /**
     * Mark every placed order notification read for the admin.
     *
     * @deprecated Use readAll().
     *
     * @return array
     */
    public function readAllNotifications()
    {
        abort_unless($this->readsOrderNotifications(), 401);

        $this->notificationRepository->markAllRead($this->admin(), AdminNotifications::AUDIENCE, [NotificationModel::ORDER_PLACED]);

        return [
            'search_results' => $this->notificationRepository->getParamsData(['limit' => 5, 'read' => 0]),
            'total_unread' => $this->notificationRepository->countUnreadOrderNotifications(),
            'success_message' => trans('admin::app.notifications.marked-success'),
        ];
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
     * Whether the signed in admin may read the orders the deprecated endpoints list.
     *
     * @deprecated The feed is narrowed by each type's permission.
     */
    protected function readsOrderNotifications(): bool
    {
        return bouncer()->hasPermission('sales.orders');
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
