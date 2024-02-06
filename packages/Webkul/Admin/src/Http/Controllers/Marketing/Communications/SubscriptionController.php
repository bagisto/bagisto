<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\DataGrids\Marketing\Communications\NewsLetterDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\SubscribersListRepository;

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
     */
    public function edit(int $id): JsonResponse
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        return new JsonResponse([
            'data'  => $subscriber,
        ]);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed
     *
     * @return void
     */
    public function update()
    {
        $validatedData = $this->validate(request(), [
            'id'            => 'required',
            'is_subscribed' => 'required|in:0,1',
        ]);

        $subscriber = $this->subscribersListRepository->findOrFail($validatedData['id']);

        $customer = $subscriber->customer;

        if ($customer) {
            $customer->subscribed_to_news_letter = $validatedData['is_subscribed'];

            $customer->save();
        }

        $result = $subscriber->update(['is_subscribed' => $validatedData['is_subscribed']]);

        if ($result) {
            return response()->json([
                'message' => trans('admin::app.marketing.communications.subscribers.index.edit.success'),
            ], 200);
        }

        return response()->json([
            'message' => trans('admin::app.marketing.communications.subscribers.index.edit.update-failed'),
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy(int $id)
    {
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
