<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\SubscribersListRepository;

/**
 * Subscription controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SubscribersListRepository
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
        return view($this->_config['view']);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed user
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
     * To unsubscribe the user without deleting the resource of the subscribed user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data = request()->all();

        $subscriber = $this->subscribersListRepository->findOrFail($id);

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

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Subscriber']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            report($e);
            
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Subscriber']));
        }

        return response()->json(['message' => false], 400);
    }
}