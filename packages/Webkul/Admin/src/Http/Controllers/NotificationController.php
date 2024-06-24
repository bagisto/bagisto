<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Notification\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin::notifications.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getNotifications()
    {
        $params = request()->except('page');

        $searchResults = count($params)
            ? $this->notificationRepository->getParamsData($params)
            : $this->notificationRepository->getAll();

        $results = isset($searchResults['notifications']) ? $searchResults['notifications'] : $searchResults;

        $statusCount = isset($searchResults['status_counts']) ? $searchResults['status_counts'] : '';

        return [
            'search_results' => $results,
            'status_count'   => $statusCount,
            'total_unread'   => $this->notificationRepository->where('read', 0)->count(),
        ];
    }

    /**
     * Update the notification is reade or not.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function viewedNotifications($orderId)
    {
        if ($notification = $this->notificationRepository->where('order_id', $orderId)->first()) {
            $notification->read = 1;

            $notification->save();

            return redirect()->route('admin.sales.orders.view', $orderId);
        }

        abort(404);
    }

    /**
     * Update the notification is reade or not.
     *
     * @return array
     */
    public function readAllNotifications()
    {
        $this->notificationRepository->where('read', 0)->update(['read' => 1]);

        $searchResults = $this->notificationRepository->getParamsData([
            'limit' => 5,
            'read'  => 0,
        ]);

        return [
            'search_results'  => $searchResults,
            'total_unread'    => $this->notificationRepository->where('read', 0)->count(),
            'success_message' => trans('admin::app.notifications.marked-success'),
        ];
    }
}
