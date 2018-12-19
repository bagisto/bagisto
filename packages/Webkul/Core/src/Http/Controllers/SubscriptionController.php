<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\SubscribersListRepository as Subscribers;
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
     * Subscription repository instance
     *
     * @var instanceof SubscribersListRepository
     */
    protected $subscribers;

    public function __construct(Subscribers $subscribers)
    {
        $this->middleware('admin');

        $this->subscribers = $subscribers;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed user
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function edit($id) {
        $subscriber = $this->subscribers->findOneByField('id', $id);

        return view($this->_config['view'])->with('subscriber', $subscriber);
    }

    /**
     * To unsubscribe the user without deleting the resource of the subscribed user
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function update($id) {
        $data = request()->all();

        $subscriber = $this->subscribers->findOneByField('id', $id);

        $result = $subscriber->update($data);

        if($result)
            session()->flash('success', trans('admin::app.customers.subscribers.update-success'));
            // session()->flash('success', 'admin::app.customers.subscribers.delete-success');
        else
            session()->flash('error', trans('admin::app.customers.subscribers.update-failed'));

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
        if($this->subscribers->delete($id))
            session()->flash('success', trans('admin::app.customers.subscribers.delete'));
        else
            session()->flash('error', trans('admin::app.customers.subscribers.delete-failed'));

        return redirect()->back();
    }
}