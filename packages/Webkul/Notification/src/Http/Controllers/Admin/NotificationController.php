<?php

namespace Webkul\Notification\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\Notification\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getNotifications()
    {
        $params = request()->all();

        if (isset($params['page'])) {
            unset($params['page']);
        }

        if (count($params)) {
            $searchResults = $this->notificationRepository->getParamsData($params);
        } else {
            $searchResults = $this->notificationRepository->with('order')->latest()->paginate(10);
        }

        return [
            'search_results' => $searchResults,
            'total_unread'   => $this->notificationRepository->where('read', 0)->count(),
        ];
    }

    /**
     * Update the notification is readed or not.
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
     * Update the notification is readed or not.
     *
     * @return array
     */
    public function readAllNotifications()
    {
        $this->notificationRepository->where('read', 0)->update(['read' => 1]);

        $params = [
            'limit' => 5,
            'read'  => 0,
        ];

        $searchResults = $this->notificationRepository->getParamsData($params);

        return [
            'search_results'  => $searchResults,
            'total_unread'    => $this->notificationRepository->where('read', 0)->count(),
            'success_message' => trans('admin::app.notification.notification-marked-success'),
        ];
    }
}
