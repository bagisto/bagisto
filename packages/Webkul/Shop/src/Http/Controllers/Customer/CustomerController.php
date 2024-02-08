<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\ProfileRequest;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected SubscribersListRepository $subscriptionRepository
    ) {
    }

    /**
     * Taking the customer to profile details page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = $this->customerRepository->find(auth()->guard('customer')->user()->id);

        return view('shop::customers.account.profile.index', compact('customer'));
    }

    /**
     * For loading the edit form page.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $customer = $this->customerRepository->find(auth()->guard('customer')->user()->id);

        return view('shop::customers.account.profile.edit', compact('customer'));
    }

    /**
     * Edit function for editing customer profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $profileRequest)
    {
        $isPasswordChanged = false;

        $data = $profileRequest->validated();

        if (empty($data['date_of_birth'])) {
            unset($data['date_of_birth']);
        }

        if (
            core()->getCurrentChannel()->theme === 'default'
            && ! isset($data['image'])
        ) {
            $data['image']['image_0'] = '';
        }

        $data['subscribed_to_news_letter'] = isset($data['subscribed_to_news_letter']);

        if (! empty($data['current_password'])) {
            if (Hash::check($data['current_password'], auth()->guard('customer')->user()->password)) {
                $isPasswordChanged = true;

                $data['password'] = bcrypt($data['new_password']);
            } else {
                session()->flash('warning', trans('shop::app.customers.account.profile.unmatch'));

                return redirect()->back();
            }
        } else {
            unset($data['new_password']);
        }

        Event::dispatch('customer.update.before');

        if ($customer = $this->customerRepository->update($data, auth()->guard('customer')->user()->id)) {
            if ($isPasswordChanged) {
                Event::dispatch('customer.password.update.after', $customer);
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

            if (request()->hasFile('image')) {
                $this->customerRepository->uploadImages($data, $customer);
            } else {
                if (isset($data['image'])) {
                    if (! empty($data['image'])) {
                        Storage::delete((string) $customer->image);
                    }

                    $customer->image = null;

                    $customer->save();
                }
            }

            session()->flash('success', trans('shop::app.customers.account.profile.edit-success'));

            return redirect()->route('shop.customers.account.profile.index');
        }

        session()->flash('success', trans('shop::app.customer.account.profile.edit-fail'));

        return redirect()->back('shop.customers.account.profile.edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->validate(request(), [
            'password' => 'required',
        ]);

        $customerRepository = $this->customerRepository->findorFail(auth()->guard('customer')->user()->id);

        try {
            if (Hash::check(request()->input('password'), $customerRepository->password)) {
                if ($customerRepository->orders->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])->first()) {
                    session()->flash('error', trans('shop::app.customers.account.profile.order-pending'));

                    return redirect()->route('shop.customers.account.profile.index');
                }

                $this->customerRepository->delete(auth()->guard('customer')->user()->id);

                session()->flash('success', trans('shop::app.customers.account.profile.delete-success'));

                return redirect()->route('shop.customer.session.index');
            }

            session()->flash('error', trans('shop::app.customers.account.profile.wrong-password'));

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.customers.account.profile.delete-failed'));

            return redirect()->route('shop.customers.account.profile.index');
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

        return view('shop::customers.account.reviews.index', compact('reviews'));
    }
}
