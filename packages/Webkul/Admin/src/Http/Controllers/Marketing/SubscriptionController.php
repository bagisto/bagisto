<?php

namespace Webkul\Admin\Http\Controllers\Marketing;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Admin\DataGrids\NewsLetterDataGrid;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SubscribersListRepository $subscribersListRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(NewsLetterDataGrid::class)->toJson();
        }

        return view('admin::marketing.email-marketing.subscribers.index');
    }

    /**
     * Subscriber Details
     *
     * @param  int  $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        return new JsonResource([
            'data'  =>  $subscriber,
        ]);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed
     *
     * @return JsonResource
     */
    public function update(): JsonResource
    {
        $id = request()->only('id');

        $subscriber = $this->subscribersListRepository->findOrFail($id);

        $customer = $subscriber->customer;

        if (!is_null($customer)) {
            $customer->subscribed_to_news_letter = request('is_subscribed');

            $customer->save();
        }

        $result = $subscriber->update(request()->only(['status']));

        if ($result) {
            return new JsonResource([
                'message' => trans('admin::app.marketing.email-marketing.newsletters.index.edit.success'),
            ]);
        } else {
            return new JsonResource([
                'message' => trans('admin::app.marketing.email-marketing.newsletters.index.edit.update-failed'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResource
     */
    public function destroy($id): JsonResource
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        try {
            $this->subscribersListRepository->delete($id);

            return new JsonResource([
                'message' => trans('admin::app.response.delete-success', ['name' => 'Subscriber']),
            ]);
        } catch (\Exception $e) {
            report($e);
        }

        return new JsonResource([
            'message' => trans('admin::app.response.delete-failed', ['name' => 'Subscriber']),
        ], 500);
    }
}
