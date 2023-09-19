<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Admin\DataGrids\Marketing\Communications\NewsLetterDataGrid;

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

        return view('admin::marketing.communications.subscribers.index');
    }

    /**
     * Subscriber Details
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id): JsonResponse
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        return new JsonResponse([
            'data'  =>  $subscriber,
        ]);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed
     *
     * @return void
     */
    public function update()
    {
        $subscriber = $this->subscribersListRepository->findOrFail(request()->id);

        $customer = $subscriber->customer;

        if (! is_null($customer)) {
            $customer->subscribed_to_news_letter = request('is_subscribed');

            $customer->save();
        }

        $result = $subscriber->update(request()->only('is_subscribed'));

        if ($result) {
            return response()->json([
                'message' => trans('admin::app.marketing.communications.subscribers.index.edit.success'),
            ], 200);
        } else {
            return response()->json([
                'message' => trans('admin::app.marketing.communications.subscribers.index.edit.update-failed'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        try {
            $this->subscribersListRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.marketing.communications.subscribers.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.marketing.communications.subscribers.delete-failed'),
        ], 500);
    }
}
