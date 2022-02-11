<?php

namespace Webkul\Core\Http\Controllers;

use Webkul\Admin\DataGrids\NewsLetterDataGrid;
use Webkul\Core\Repositories\SubscribersListRepository;

class SubscriptionController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Subscribers list repository.
     *
     * @var \Webkul\Core\Repositories\SubscribersListRepository
     */
    protected $subscribersListRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SubscribersListRepository  $subscribersListRepository
     * @return void
     */
    public function __construct(SubscribersListRepository $subscribersListRepository)
    {
        $this->subscribersListRepository = $subscribersListRepository;

        $this->_config = request('_config');
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

        return view($this->_config['view']);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        return view($this->_config['view'])->with('subscriber', $subscriber);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data = request()->all();

        $subscriber = $this->subscribersListRepository->findOrFail($id);

        $customer = $subscriber->customer;

        if (! is_null($customer)) {
            $customer->subscribed_to_news_letter = $data['is_subscribed'];
            $customer->save();
        }

        $result = $subscriber->update($data);

        if ($result) {
            session()->flash('success', trans('admin::app.customers.subscribers.update-success'));
        } else {
            session()->flash('error', trans('admin::app.customers.subscribers.update-failed'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscriber = $this->subscribersListRepository->findOrFail($id);

        try {
            $this->subscribersListRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Subscriber'])]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Subscriber'])], 500);
    }
}
