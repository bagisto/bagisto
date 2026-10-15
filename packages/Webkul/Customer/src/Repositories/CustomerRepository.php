<?php

namespace Webkul\Customer\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Contracts\CustomerGroup;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\OrderProxy;

class CustomerRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CustomerContract::class;
    }

    /**
     * Check if customer has order pending or processing.
     *
     * @param  Customer
     * @return bool
     */
    public function haveActiveOrders($customer)
    {
        return $customer->orders->pluck('status')->contains(function ($val) {
            return $val === 'pending' || $val === 'processing';
        });
    }

    /**
     * Returns current customer group.
     *
     * @return CustomerGroup
     */
    public function getCurrentGroup()
    {
        $customer = auth()->guard()->user();

        return $customer->group ?? $this->customerGroupRepository->getGuestGroup();
    }

    /**
     * Upload customer's images.
     *
     * @param  array  $data
     * @param  Customer  $customer
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $customer, $type = 'image')
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type.'.'.$imageId;
                $dir = 'customer/'.$customer->id;

                if ($request->hasFile($file)) {
                    if ($customer->{$type}) {
                        Storage::delete($customer->{$type});
                    }

                    $customer->{$type} = $request->file($file)->store($dir);
                    $customer->save();
                }
            }
        } else {
            if ($customer->{$type}) {
                Storage::delete($customer->{$type});
            }

            $customer->{$type} = null;
            $customer->save();
        }
    }

    /**
     * Sync new registered customer data.
     *
     * @param  CustomerContract  $customer
     * @return mixed
     */
    public function syncNewRegisteredCustomerInformation($customer)
    {
        OrderProxy::query()->where('customer_email', $customer->email)->update([
            'is_guest' => 0,
            'customer_id' => $customer->id,
            'customer_type' => Customer::class,
        ]);

        $orders = OrderProxy::query()->where('customer_id', $customer->id)->get();

        $orders->each(function ($order) use ($customer) {
            $order->addresses()->update([
                'customer_id' => $customer->id,
            ]);

            $order->shipments()->update([
                'customer_id' => $customer->id,
                'customer_type' => Customer::class,
            ]);

            $order->downloadable_link_purchased()->update([
                'customer_id' => $customer->id,
            ]);
        });
    }
}
