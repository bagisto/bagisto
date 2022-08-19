<?php

namespace Webkul\Customer\Http\Controllers;

use Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Customer\Http\Requests\CustomerProfileRequest;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Shop\Mail\SubscriptionEmail;

class CustomerController extends Controller
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
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @param  \Webkul\Core\Repositories\SubscribersListRepository  $subscriptionRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected SubscribersListRepository $subscriptionRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Taking the customer to profile details page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = $this->customerRepository->find(auth()->guard('customer')->user()->id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * For loading the edit form page.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $customer = $this->customerRepository->find(auth()->guard('customer')->user()->id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Edit function for editing customer profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerProfileRequest $customerProfileRequest)
    {
        $isPasswordChanged = false;

        $data = $customerProfileRequest->validated();

        if (
            isset($data['date_of_birth'])
            && $data['date_of_birth'] == ''
        ) {
            unset($data['date_of_birth']);
        }

        if (
            core()->getCurrentChannel()->theme === 'default'
            && ! isset($data['image'])
        ) {
            $data['image']['image_0'] = '';
        }

        $data['subscribed_to_news_letter'] = isset($data['subscribed_to_news_letter']);

        if (isset($data['oldpassword'])) {
            if (
                $data['oldpassword'] != ''
                || $data['oldpassword'] != null
            ) {
                if (Hash::check($data['oldpassword'], auth()->guard('customer')->user()->password)) {
                    $isPasswordChanged = true;

                    $data['password'] = bcrypt($data['password']);
                } else {
                    session()->flash('warning', trans('shop::app.customer.account.profile.unmatch'));

                    return redirect()->back();
                }
            } else {
                unset($data['password']);
            }
        }

        Event::dispatch('customer.update.before');

        if ($customer = $this->customerRepository->update($data, auth()->guard('customer')->user()->id)) {
            if ($isPasswordChanged) {
                Event::dispatch('user.admin.update-password', $customer);
            }

            Event::dispatch('customer.update.after', $customer);

            if ($data['subscribed_to_news_letter']) {
                $subscription = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

                if ($subscription) {
                    $this->subscriptionRepository->update([
                        'customer_id'   => $customer->id,
                        'is_subscribed' => 1,
                    ], $subscription->id);
                } else {
                    $this->subscriptionRepository->create([
                        'email'         => $data['email'],
                        'customer_id'   => $customer->id,
                        'channel_id'    => core()->getCurrentChannel()->id,
                        'is_subscribed' => 1,
                        'token'         => $token = uniqid(),
                    ]);

                    try {
                        Mail::queue(new SubscriptionEmail([
                            'email' => $data['email'],
                            'token' => $token,
                        ]));
                    } catch (\Exception $e) {
                    }
                }
            } else {
                $subscription = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

                if ($subscription) {
                    $this->subscriptionRepository->update([
                        'customer_id'   => $customer->id,
                        'is_subscribed' => 0,
                    ], $subscription->id);
                }
            }

            $this->customerRepository->uploadImages($data, $customer);

            session()->flash('success', trans('shop::app.customer.account.profile.edit-success'));

            return redirect()->route($this->_config['redirect']);
        }

        session()->flash('success', trans('shop::app.customer.account.profile.edit-fail'));

        return redirect()->back($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = auth()->guard('customer')->user()->id;

        $data = request()->all();

        $customerRepository = $this->customerRepository->findorFail($id);

        try {
            if (Hash::check($data['password'], $customerRepository->password)) {
                $orders = $customerRepository->all_orders->whereIn('status', ['pending', 'processing'])->first();

                if ($orders) {
                    session()->flash('error', trans('admin::app.response.order-pending', ['name' => 'Customer']));

                    return redirect()->route($this->_config['redirect']);
                } else {
                    $this->customerRepository->delete($id);

                    session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Customer']));

                    return redirect()->route('customer.session.index');
                }
            } else {
                session()->flash('error', trans('shop::app.customer.account.address.delete.wrong-password'));

                return redirect()->back();
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Customer']));

            return redirect()->route($this->_config['redirect']);
        }
    }

    /**
     * Load the view for the customer account panel, showing approved reviews.
     *
     * @return \Illuminate\View\View
     */
    public function reviews()
    {
        $reviews = $this->productReviewRepository->getCustomerReview();

        return view($this->_config['view'], compact('reviews'));
    }
}
