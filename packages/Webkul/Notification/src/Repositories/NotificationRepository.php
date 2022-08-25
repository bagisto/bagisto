<?php

namespace Webkul\Notification\Repositories;

use Webkul\Core\Eloquent\Repository;

class NotificationRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Notification\Contracts\Notification';
    }

    /**
     * Return Filtered Notification resources
     *
     * @return objects
     */
    public function getParamsData($params)
    {
        if (
            isset($params['id'])
            && isset($params['status'])
        ) {
            return $params['status'] != 'All' ? $this->model->where(function($qry) use ($params) {
                    $qry->whereHas('order',function ($q) use ($params) {
                        $q->where(['status' => $params['status']]);
                    });
                })->where('order_id', $params['id'])->with('order')->paginate(10)  : $this->model->where('order_id', $params['id'])->with('order')->paginate(10) ;
        } elseif (isset($params['status'])) {
            return $params['status'] != 'All' ? $this->model->where(function($qry) use ($params) {
                $qry->whereHas('order',function ($q) use ($params) {
                    $q->where(['status' => $params['status']]);
                });
            })->with('order')->paginate(10): $this->model->with('order')->latest()->paginate(10);
        } elseif(
            isset($params['read'])
            && isset($params['limit'])
        ) {
            return $this->model->where('read', $params['read'])->limit($params['limit'])->with('order')->latest()->paginate($params['limit']);
        } elseif(isset($params['limit'])) {
            return $this->model->limit($params['limit'])->with('order')->latest()->paginate($params['limit']);
        } elseif(isset($params['id'])) {
            return $this->model->where('order_id', $params['id'])->with('order')->paginate(10);
        }

        return [];
    }
}